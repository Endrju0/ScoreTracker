<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use core\RoleUtils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\LeaderboardForm;

class SeasonManagementCtrl {

    private $user;
    private $form;
    private $allSeasonsList;

    public function __construct() {
      $this->loadUser();
      $this->form = new LeaderboardForm();
    }

    private function loadUser() {
      $this->user = unserialize(ParamUtils::getFromSession('user'));
    }

    private function saveUser() {
      SessionUtils::storeObject('user', $this->user);
    }

    public function action_seasonManagement() {
      $this->allSeasonsList= App::getDB()->select("season", [
            "id",
            "date",
            "name",
            "active"
          ],[
            "party_id" => $this->user->party_id
          ]);

        $this->generateView();
    }

    public function action_setActiveSeason() { //ustawienie konkretnego sezonu jako aktywnego dla danej grupy
      $this->form->id = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
      if(!App::getMessages()->isError()) {
        $oldActiveSeasonId = App::getDB()->get("season", "id", [
          "AND" => [
            "active" => 1,
            "party_id" => $this->user->party_id
          ]
        ]);
        App::getDB()->update("season", [
              "active" => 1
            ],[
              "id" => $this->form->id
            ]);
        App::getDB()->update("season", [
              "active" => 0
            ],[
              "id" => $oldActiveSeasonId
            ]);
      }
      App::getRouter()->forwardTo('seasonManagement');
    }

    public function action_deleteSeason() { //usuwanie konkretnego sezonu
      $this->form->id = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
      try {
        if(!App::getMessages()->isError()) {
          //Usunięcie wszystkich wpisów z wynikami z tabeli tracker
          App::getDB()->delete("tracker", [
          		"season_id" => $this->form->id
          ]);
          //Usunięcie sezonu
          App::getDB()->delete("season", [
          		"id" => $this->form->id
          ]);
        Utils::addInfoMessage('Season successfully deleted!');
        }
      } catch (\PDOException $e) {
          Utils::addErrorMessage('Unexpected error!');
          if (App::getConf()->debug)
              Utils::addErrorMessage($e->getMessage());
      }
      App::getRouter()->forwardTo('seasonManagement');
    }

    public function action_newSeason() { //nowy sezon
      $this->form->newSeasonName = ParamUtils::getFromPost('newSeasonName');

      //walidacja stringa nazwy nowego sezonu
      if (empty($this->form->newSeasonName)) {
          Utils::addErrorMessage('New season name is empty!');
      }
      if (strlen($this->form->newSeasonName) > 45) {
          Utils::addErrorMessage('New season name cannot be longer than 45 characters!');
      }

      //jeśli nie było problemów to dodanie wpisu do bazy
      if(!App::getMessages()->isError()) {
        try {
            //id starego aktywnego sezonu
            $oldActiveSeasonId = App::getDB()->get("season", "id", [
              "AND" => [
                "active" => 1,
                "party_id" => $this->user->party_id
              ]
            ]);
            //dodanie nowego sezonu i ustawienie go jako aktywny
            App::getDB()->insert("season", [
                "date" => date("Y-m-d H:i:s"),
                "party_id" => $this->user->party_id,
                "name" => $this->form->newSeasonName,
                "active" => 1
            ]);
            //ustaweinie starego sezonu na nieaktywny
            App::getDB()->update("season", [
                  "active" => 0
                ],[
                  "id" => $oldActiveSeasonId
                ]);
            //przydzielenie każdemu członkowi party zerowych statystyk w nowym sezonie
            /*App::getDB()->insert("tracker", [
                "[>]user" => ["user_id" => "id"],
                "[>]season" => ["season_id" => "id"]
                ], [
                  "tracker.wins" => 0,
                  "tracker.amount" => 0
                ],[
                "AND" => [
                  "user.party_id" => $this->user->party_id,
                  "season.party_id" => $this->user->party_id,
                  "season.active" => 1
                ]
              ]);*/
            Utils::addInfoMessage('New season successfully added!');
        } catch (\PDOException $e) {
            Utils::addErrorMessage('Unexpected error!');
            if (App::getConf()->debug)
                Utils::addErrorMessage($e->getMessage());
        }
      }
      App::getRouter()->forwardTo('seasonManagement');
    }

    public function generateView() {
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->assign('seasonList', $this->allSeasonsList);
        App::getSmarty()->display('SeasonManagement.tpl');
    }

}
