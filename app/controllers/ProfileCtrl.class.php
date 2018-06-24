<?php
namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use core\RoleUtils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\LeaderboardForm;

class ProfileCtrl {

    private $user;
    private $partyName;

    public function __construct() {
    }

    private function loadUser() {
        $this->user = unserialize(ParamUtils::getFromSession('user'));
    }

    private function saveUser() {
        SessionUtils::storeObject('user', $this->user);
    }

    //Sprawdzenie czy w party (funkcja dla widoku - pokazanie buttona do opuszczenia party)
    public function isInParty() {
       $this->loadUser();
        if ($this->user->party_id == NULL) {
            return false;
        } else {
          $this->partyName = App::getDB()->get("party", "name", [
              "id" => $this->user->party_id
          ]);
          return true;
        }
    }

    public function action_leaveParty() {
        try {
            $this->loadUser();
            App::getDB()->update("user", [
                "party_id" => null,
                "role_id" => 3
                    ], [
                "id" => $this->user->id
            ]);

            $this->user->party_id = NULL;
            $this->user->role_id = 3;
            $this->user->role = App::getDB()->get("role", "role", [
                "id" => $this->user->role_id
            ]);
            $this->saveUser();
            RoleUtils::removeRole('moderator');
            RoleUtils::addRole('user');
        } catch (\PDOException $e) {
            Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
            if (App::getConf()->debug)
                Utils::addErrorMessage($e->getMessage());
        }
        $this->generateView();
    }

    public function action_profile() {
        $this->generateView();
    }

    public function generateGravatarUrl() {
      $this->loadUser();
      return $gravatarUrl = 'http://gravatar.com/avatar/'.md5($this->user->email).'?d=monsterid&s=200';
    }

    public function generateView() {
        App::getSmarty()->assign('gravatar', $this->generateGravatarUrl());
        App::getSmarty()->assign('isInParty', $this->isInParty());
        App::getSmarty()->assign('partyName', $this->partyName);
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->display('ProfileView.tpl');
    }
}
