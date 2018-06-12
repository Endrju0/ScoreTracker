<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\SessionUtils;
use core\Validator;
use app\forms\LeaderboardForm;

class LeaderboardCtrl {

    private $form;

    public function __construct() {
        $this->form = new LeaderboardForm();
    }

    public function action_leaderboard() {
        $this->checkParty();
          try {
              $this->form->trackerList = App::getDB()->select("tracker", [
                  "[>]user" => ["user_id" => "id"]
                ], [
                  "user.login",
                  "tracker.wins",
                  "tracker.amount",
              ]);
              for($i=0;$i<5;$i++) {
                  if($this->form->trackerList[$i]['amount'] == 0) {
                    $this->form->trackerList[$i]['win_ratio'] = 0;
                  } else {
                    $this->form->trackerList[$i]['win_ratio'] = $this->form->trackerList[$i]['wins'] / $this->form->trackerList[$i]['amount'];
                  }
                }
              //$this->test = implode(" | ",$this->form->trackerList[1]);
          } catch (\PDOException $e) {
              Utils::addErrorMessage('Wystąpił błąd podczas sprawdzania party');
              if (App::getConf()->debug)
                  Utils::addErrorMessage($e->getMessage());
          }

        $this->generateView();
    }

    private function loadUserId() {
        $this->form->uid = SessionUtils::load('sessionId', true);
    }

    public function checkParty() {
        $this->loadUserId();
        $pid = App::getDB()->get("user", "party_id", [
            "id" => $this->form->uid
        ]);
        if ($pid == NULL) {
            try {
                $this->form->partyList = App::getDB()->select("party", [
                    "id",
                    "name"
                ]);
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił błąd podczas sprawdzania party');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
            return false;
        } else {
            $this->form->partyName = App::getDB()->get("party", "name", [
                "id" => $pid
            ]);
            return true;
        }
    }

    public function action_createParty() {
        $v = new Validator();

        $this->form->newPartyName = $v->validateFromPost('newPartyName', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Podaj nazwę party',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Party powinno mieć od 2 do 45 znaków'
        ]);

        if (App::getDB()->count("party", [
                    "name" => $this->form->newPartyName
                ]) > 0) {
            Utils::addErrorMessage('Party o takiej nazwie nie istnieje!');
        } else if (isset($this->form->newPartyName) && !empty($this->form->newPartyName)) {
            try {
                App::getDB()->insert("party", [
                    "name" => $this->form->newPartyName
                ]);
                Utils::addInfoMessage('Pomyślnie utworzono party');
                $pid = App::getDB()->get("party", "id", [
                    "name" => $this->form->newPartyName
                ]);
                $this->loadUserId();
                App::getDB()->update("user", [
                    "party_id" => $pid,
                    "role_id" => 2
                        ], [
                    "id" => $this->form->uid
                ]);
                App::getRouter()->forwardTo('leaderboard');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }

        $this->generateView();
    }

    public function action_joinParty() {
        $this->loadUserId();
        $v = new Validator();

        $this->form->joinParty = $v->validateFromPost('party', [
            'trim' => true,
            'required' => true
        ]);
        try {
            if (App::getDB()->count("party", [
                        "name" => $this->form->joinParty
                    ]) > 0) {
                $partyid = App::getDB()->get("party", "id", [
                    "name" => $this->form->joinParty
                ]);
                App::getDB()->update("user", [
                    "party_id" => $partyid,
                        ], [
                    "id" => $this->form->uid
                ]);
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
        App::getSmarty()->display('LeaderboardView.tpl');
    }

}
