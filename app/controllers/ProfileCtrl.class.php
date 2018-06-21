<?php
namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use core\RoleUtils;
use core\ParamUtils;
use app\forms\LeaderboardForm;

class ProfileCtrl {

    private $user;

    public function __construct() {
        $this->loadUser();
    }
    private function loadUser() {
        $this->user = unserialize(ParamUtils::getFromSession('user'));
    }
    public function isInParty() {
        $pid = App::getDB()->get("user", "party_id", [
            "id" => $this->user->id
        ]);
        if ($pid == NULL) {
            return false;
        } else
            return true;
    }
    public function action_leaveParty() {
        try {
            App::getDB()->update("user", [
                "party_id" => null,
                "role_id" => 3
                    ], [
                "id" => $this->user->id
            ]);
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
      $email = App::getDB()->get("user", "email", [
          "id" => $this->user->id
      ]);
      return $gravatarUrl = 'http://gravatar.com/avatar/'.md5($email).'?d=monsterid&s=200';
    }
    public function generateView() {
        App::getSmarty()->assign('gravatar', $this->generateGravatarUrl());
        App::getSmarty()->assign('isInParty', $this->isInParty());
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->display('ProfileView.tpl');
    }
}
