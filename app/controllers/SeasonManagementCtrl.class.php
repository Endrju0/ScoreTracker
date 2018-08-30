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

    public function generateView() {
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->assign('seasonList', $this->allSeasonsList);
        App::getSmarty()->display('SeasonManagement.tpl');
    }

}
