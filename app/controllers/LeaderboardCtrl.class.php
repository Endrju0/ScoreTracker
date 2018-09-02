<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use core\RoleUtils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\LeaderboardForm;

class LeaderboardCtrl {

    private $form;
    private $user;
    private $selectableUsers;

    public function __construct() {
        $this->form = new LeaderboardForm();
    }

    private function loadUser() {
      $this->user = unserialize(ParamUtils::getFromSession('user'));
    }

    private function saveUser() {
      SessionUtils::storeObject('user', $this->user);
    }

    /*pobranie zawartości tabeli wyników i przekazanie jej do $this->form->trackerList*/
    public function action_leaderboard() {
        $this->checkParty();
          try {
              $this->loadUser();
              if (App::getConf()->debug) {
                Utils::addInfoMessage('--------LEADERBOARD--------');
                Utils::addInfoMessage('user id: '.$this->user->id.' ;');
                Utils::addInfoMessage('user login: '.$this->user->login.' ;');
                Utils::addInfoMessage('user email: '.$this->user->email.' ;');
                Utils::addInfoMessage('user role_id: '.$this->user->role_id.' ;');
                Utils::addInfoMessage('user party_id: '.$this->user->party_id.' ;');
                Utils::addInfoMessage('user role: '.$this->user->role.' ;');
                Utils::addInfoMessage('user last_login: '.$this->user->last_login.' ;');
              }
              $pid = App::getDB()->get("user", "party_id", [
                  "id" => $this->user->id
              ]);
              $this->form->trackerList = App::getDB()->select("tracker", [
                  "[>]user" => ["user_id" => "id"],
                  "[>]season" => ["season_id" => "id"]
                  ], [
                    "tracker.id",
                    "user.login",
                    "tracker.wins",
                    "tracker.amount"
                  ],[
                  "AND" => [
                    "user.party_id" => $pid,
                    "season.party_id" => $pid,
                    "season.active" => 1
                  ]
                  ]);
              for($i=0; $i<count($this->form->trackerList); $i++) {
                  if($this->form->trackerList[$i]['amount'] == 0) {
                    $this->form->trackerList[$i]['win_ratio'] = 0;
                  } else {
                    $this->form->trackerList[$i]['win_ratio'] = round($this->form->trackerList[$i]['wins'] / $this->form->trackerList[$i]['amount'],2,PHP_ROUND_HALF_DOWN);
                  }
                }
          } catch (\PDOException $e) {
              Utils::addErrorMessage('Wystąpił błąd podczas sprawdzania party');
              if (App::getConf()->debug)
                  Utils::addErrorMessage($e->getMessage());
          }

          //Dodanie nowego użytkownika do tabeli tracker
         try {
                //zwraca id aktywnego sezonu dla danego party
                $activeSeasonId = App::getDB()->get("season", "id", [
                    "AND" => [
                      "active" => 1,
                      "party_id" => $this->user->party_id
                    ]
                  ]);

                  //zwraca id wszystkich osób, którzy brali jakikolwiek udział w dowolnym sezonie
                  $tmp = App::getDB()->select("tracker", [
                       "user_id"
                   ]);

                   //konwersja tablicy wielowymiarowej do dwuwymiarowej, gdyż medoo nie przyjmuje wielowymiarowych
                  $conditionalToSearch = array_column($tmp, 'user_id');

                   //zwraca login tych, którzy nie brali udziału w obecnym sezonie
                  $this->selectableUsers = App::getDB()->select("user", [
                     "[>]tracker" => ["id" => "user_id"]
                  ], [
                      "user.id",
                      "user.login"
                  ], [
                    "OR" => [
                      "tracker.season_id[!]" => $activeSeasonId, //id tych sezonów, które nie są obecnym
                      "user.id[!]" => $conditionalToSearch //wszystkie osoby, które nigdy nie brały udziału w grupach
                    ],
                    "user.party_id" => $this->user->party_id
                  ]);

          } catch (\PDOException $e) {
              Utils::addErrorMessage('Wystąpił błąd podczas sprawdzania party');
              if (App::getConf()->debug)
                  Utils::addErrorMessage($e->getMessage());
          }

        $this->generateView();
    }

    public function action_incWins() { //zwiększenie wygranych o 1
      $this->form->id = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
      if(!App::getMessages()->isError()) {
        $this->form->trackerList = App::getDB()->update("tracker", [
              "wins[+]" => 1,
              "amount[+]" => 1
            ],[
              "id" => $this->form->id
            ]);
      }
      App::getRouter()->forwardTo('leaderboard');
    }

    public function action_incAmount() { //zwiększenie liczby gier o 1
      $this->form->id = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
      if(!App::getMessages()->isError()) {
        $this->form->trackerList = App::getDB()->update("tracker", [
              "amount[+]" => 1
            ],[
              "id" => $this->form->id
            ]);
      }
      App::getRouter()->forwardTo('leaderboard');
    }

    public function action_decWins() { //zmniejszenie wygranych o 1
      //pobranie id rekordu z tabeli 'tracker', który będziemy aktualizować
      $this->form->id = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
      //pobranie ilości wygranych
      $validateValue = ParamUtils::getFromPost('validateValue', true, 'Błędne wywołanie aplikacji');

      //jeśli wygranych jest mniej niż 1 to kończymy akcję
      if($validateValue < 1)
      Utils::addErrorMessage('Ilość wygranych nie może być mniejsza od 0!');

      if(!App::getMessages()->isError()) {
        $this->form->trackerList = App::getDB()->update("tracker", [
              "wins[-]" => 1,
            ],[
              "id" => $this->form->id
            ]);
      }
      App::getRouter()->forwardTo('leaderboard');
    }

    public function action_decAmount() { //zmniejszenie liczby gier o 1
      //pobranie id rekordu z tabeli 'tracker', który będziemy aktualizować
      $this->form->id = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
      //pobranie ilości gier
      $validateValue = ParamUtils::getFromPost('validateValue', true, 'Błędne wywołanie aplikacji');

      //jeśli gier jest mniej niż 1 to kończymy akcję
      if($validateValue < 1)
      Utils::addErrorMessage('Ilość gier nie może być mniejsza od 0!');

      if(!App::getMessages()->isError()) {
        $this->form->trackerList = App::getDB()->update("tracker", [
              "amount[-]" => 1
            ],[
              "id" => $this->form->id
            ]);
      }
      App::getRouter()->forwardTo('leaderboard');
    }

    public function action_addMemberToSeason() {
      $v = new Validator();
      //pobranie nazwy party do którego chcemy dołączyć z formularza
      $this->form->member = $v->validateFromPost('memberList', [
          'trim' => true,
          'required' => true,
          'required_message' => 'Podaj login członka',
          'min_length' => 2,
          'max_length' => 30,
          'validator_message' => 'Login powinien mieć od 2 do 30 znaków'
      ]);
      try {
          $this->loadUser();
          //sprawdzenie czy taki użytkownik istnieje, jeśli tak to dodanie go do tabeli tracker
          if (App::getDB()->has("user", [
          	"AND" => [
              "login" => $this->form->member,
              "party_id" => $this->user->party_id
        	   ]
        	])) {
             $tmpId = App::getDB()->select("user", [
                "id"
             ], [
                "login" => $this->form->member
             ]);
             $tmpId = array_column($tmpId, 'id');

             $tmpSeason = App::getDB()->select("season", [
                "id"
             ], [
                "active" => 1,
                "party_id" => $this->user->party_id
             ]);
             $tmpSeason = array_column($tmpSeason, 'id');

            App::getDB()->insert("tracker", [
            	"user_id" => $tmpId[0],
            	"wins" => 0,
            	"amount" => 0,
              "season_id" => $tmpSeason[0]
            ]);
          } else {
              Utils::addErrorMessage('Nie ma takiego członka w party');
          }
      } catch (\PDOException $e) {
          Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
          if (App::getConf()->debug)
              Utils::addErrorMessage($e->getMessage());
      }
      App::getRouter()->forwardTo('leaderboard');
    }

    /*Funkcja sprawdzająca czy user jest w party - jeśli jest to nie wyświetlamy
      formularza odpowiadającego za dołaczenie/założenie party*/
    public function checkParty() {
        $this->loadUser();

        //Sprawdzenie czy user jest w party
        if ($this->user->party_id == NULL) {
            try {
                //Jeśli nie to wyświetlamy mu listę dostępnych do wyboru
                $this->form->partyList = App::getDB()->select("party", [
                    "id",
                    "name"
                ]);
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił błąd podczas sprawdzania party');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        } else {
            //Jeśli jest w party to wyświetlamy jej nazwę zamiast formularza
            $this->form->partyName = App::getDB()->get("party", "name", [
                "id" => $this->user->party_id
            ]);

            /*Jeśli nie jest utworzony sezon dla grupy, to przekierowuje go do
              akcji seasonManagement, gdzie może w/w sezon utworzyć. Bez takiego
              sezonu aplikacja się wysypuje przy dodawanu nowych użytkowników do
              tabeli. (przez konstrukcję bazy)*/
              if(!App::getDB()->has("season", [
              	"party_id" => $this->user->party_id
              ]) && !($this->user->role_id == 3)) {
                echo '<script type="text/javascript">
                     window.location = "seasonManagement"
                </script>';
              }
        }
        $this->saveUser();
    }

    public function action_createParty() {
        $v = new Validator();

        //pobranie nazwy party, które chcemy założyć
        $this->form->newPartyName = $v->validateFromPost('newPartyName', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Podaj nazwę party',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Party powinno mieć od 2 do 45 znaków'
        ]);

        /*Sprawdzamy czy takie party istnieje, jeśli tak to przechodzimy do
          wyświetlenia widoku*/
        if (App::getDB()->count("party", [
                    "name" => $this->form->newPartyName
                ]) > 0) {
            Utils::addErrorMessage('Party o takiej nazwie nie istnieje!');
        } else if (isset($this->form->newPartyName) && !empty($this->form->newPartyName)) {
            try {
                // Dodanie nowego party do tabeli "party"
                App::getDB()->insert("party", [
                    "name" => $this->form->newPartyName
                ]);
                Utils::addInfoMessage('Pomyślnie utworzono party');
                $this->loadUser();

                //Pobranie id od party o danej nazwie
                $this->user->party_id = App::getDB()->get("party", "id", [
                    "name" => $this->form->newPartyName
                ]);

                if(RoleUtils::inRole('admin')) {
                  App::getDB()->update("user", [
                      "party_id" => $this->user->party_id,
                      "party_member_since" => date("Y-m-d H:i:s")
                          ], [
                      "id" => $this->user->id
                  ]);
                } else {
                  // Zaktualizowanie informacji w tabeli "user" o party danego użytkownika
                  App::getDB()->update("user", [
                      "party_id" => $this->user->party_id,
                      "role_id" => 2, //rola moderatora
                      "party_member_since" => date("Y-m-d H:i:s")
                          ], [
                      "id" => $this->user->id
                  ]);

                  //Zapisanie roli do sesji
                  $this->user->role_id = 2;
                  $this->user->role = App::getDB()->get("role", "role", [
                      "id" => $this->user->role_id
                  ]);
                  RoleUtils::removeRole('user');
                  RoleUtils::addRole('moderator');
                }
                $this->saveUser();
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }
        App::getRouter()->forwardTo('leaderboard');
    }

    public function action_joinParty() {
        $v = new Validator();

        //pobranie nazwy party do którego chcemy dołączyć z formularza
        $this->form->joinParty = $v->validateFromPost('party', [
            'trim' => true,
            'required' => true
        ]);
        try {
            $this->loadUser();
            //sprawdzenie czy takie party istnieje, jeśli tak to aktualizacja bazy i sesji
            if (App::getDB()->count("party", [
                        "name" => $this->form->joinParty
                    ]) > 0) {
                $this->user->party_id = App::getDB()->get("party", "id", [
                    "name" => $this->form->joinParty
                ]);
                App::getDB()->update("user", [
                    "party_id" => $this->user->party_id,
                    "party_member_since" => date("Y-m-d H:i:s")
                        ], [
                    "id" => $this->user->id
                ]);
                $this->saveUser();
            } else {
                Utils::addErrorMessage('Party o takiej nazwie nie istnieje!');
            }
        } catch (\PDOException $e) {
            Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
            if (App::getConf()->debug)
                Utils::addErrorMessage($e->getMessage());
        }
        App::getRouter()->forwardTo('leaderboard');
    }

    public function generateView() {
        App::getSmarty()->assign('partyName', $this->form->partyName);
        App::getSmarty()->assign('partyList', $this->form->partyList);
        App::getSmarty()->assign('trackerList', $this->form->trackerList);
        App::getSmarty()->assign('selectableUsers', $this->selectableUsers);
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->display('LeaderboardView.tpl');
    }

}
