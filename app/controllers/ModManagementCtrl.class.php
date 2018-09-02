<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use core\RoleUtils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\LeaderboardForm;

class ModManagementCtrl {

    private $user;
    private $partyUserList;

    public function __construct() {
      $this->loadUser();
    }

    private function loadUser() {
      $this->user = unserialize(ParamUtils::getFromSession('user'));
    }

    private function saveUser() {
      SessionUtils::storeObject('user', $this->user);
    }

    public function action_modManagement() {
      $this->partyUserList = App::getDB()->select("user", [
            "id",
            "login"
          ],[
            "party_id" => $this->user->party_id,
            "role_id" => 3
          ]);

        $this->generateView();
    }

    public function action_passMod() {
        $chosenUserId = ParamUtils::getFromPost('id', true, 'Błędne wywołanie aplikacji');
        if(!App::getMessages()->isError()) {
          App::getDB()->update("user", [
                "role_id" => 2
              ],[
                "id" => $chosenUserId
              ]);
          App::getDB()->update("user", [
                "role_id" => 3
              ],[
                "id" => $this->user->id
              ]);
              $this->user->role_id = 3;
              $this->user->role = 'user';
              $this->saveUser();
              RoleUtils::removeRole('moderator');
              RoleUtils::addRole('user');
        }
        App::getRouter()->forwardTo('leaderboard');
    }


    public function generateView() {
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->assign('partyUserList', $this->partyUserList);
        App::getSmarty()->display('ModManagement.tpl');
    }

}
