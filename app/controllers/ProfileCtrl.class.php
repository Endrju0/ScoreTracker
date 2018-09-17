<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use core\RoleUtils;
use core\ParamUtils;
use core\SessionUtils;
use app\forms\ProfileForm;

class ProfileCtrl {

    private $user;
    private $partyName;
    private $form;

    public function __construct() {
        $this->user = unserialize(ParamUtils::getFromSession('user'));
    }

    private function saveUser() {
        SessionUtils::storeObject('user', $this->user);
    }

    //Sprawdzenie czy w party (funkcja dla widoku - pokazanie buttona do opuszczenia party)
    public function isInParty() {
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
            if (RoleUtils::inRole('admin')) {
                App::getDB()->update("user", [
                    "party_id" => null,
                    "party_member_since" => null
                        ], [
                    "id" => $this->user->id
                ]);
            } else { //wszyscy, którzy nie są adminem
                if (App::getDB()->count("user", [
                            "party_id" => $this->user->party_id,
                        ]) < 2) { //usunięcie grupy gdy zostanie tylko 1 osoba
                    if (App::getDB()->has("season", [
                                "party_id" => $this->user->party_id,
                            ])) {
                        $tmp = App::getDB()->select("season", [
                            "id"
                                ], [
                            "party_id" => $this->user->party_id
                        ]);
                        $seasonsOfGroup = array_column($tmp, 'id');

                        App::getDB()->delete("tracker", [
                            "season_id" => $seasonsOfGroup
                        ]);
                        App::getDB()->delete("season", [
                            "party_id" => $this->user->party_id
                        ]);
                    }

                    App::getDB()->query("SET FOREIGN_KEY_CHECKS=0");

                    App::getDB()->delete("party", [
                        "id" => $this->user->party_id
                    ]);

                    App::getDB()->query("SET FOREIGN_KEY_CHECKS=1");
                }
                if ($this->user->role_id == 2) { //przekazanie moderatora najstarszemu użytkownikowi
                    //pobranie id najstarszego użytkownika (user) w party
                    $oldestMember = App::getDB()->get("user", [
                        "id"
                            ], [
                        "AND" => [
                            "party_id" => $this->user->party_id,
                            "role_id" => 3
                        ],
                        "ORDER" => "party_member_since"
                    ]);

                    App::getDB()->update("user", [
                        "role_id" => 2
                            ], [
                        "id" => $oldestMember['id']
                    ]);
                }
                //opuszczenie grupy
                App::getDB()->delete("tracker", [
                    "user_id" => $this->user->id
                ]);
                //wyzerowanie informacji dotyczacych grupy
                App::getDB()->update("user", [
                    "party_id" => null,
                    "party_member_since" => null,
                    "role_id" => 3
                        ], [
                    "id" => $this->user->id
                ]);
                $this->user->role_id = 3;
                $this->user->role = 'user';
                RoleUtils::removeRole('moderator');
                RoleUtils::addRole('user');
            }
            $this->user->party_id = NULL;
            $this->saveUser();
            Utils::addInfoMessage('You left party successfully.');
        } catch (\PDOException $e) {
            Utils::addErrorMessage('An unexpected error occurred during leaving party (database error).');
            if (App::getConf()->debug)
                Utils::addErrorMessage($e->getMessage());
        }
        $this->generateView();
    }

    public function action_profile() {
        $this->generateView();
    }

    public function action_changePassword() {
        $v = new Validator();
        $this->form = new ProfileForm();

        //walidacja
        $this->form->oldPassword = $v->validateFromPost('passOld', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Enter the old password.',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Password should have between 2 to 45 characters.'
        ]);
        $this->form->newPassword = $v->validateFromPost('passNew', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Enter the new password.',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Password should have between 2 to 45 characters.'
        ]);

        if (!App::getMessages()->isError()) {
            //sprawdzenie czy stare hasło jest poprawne, jeśli tak to zmieniamy hasło
            $salt = 'ScoreTrackerProject';

            if (App::getDB()->has("user", [
                        "login" => $this->user->login,
                        "password" => hash('SHA512', $this->form->oldPassword . $salt)
                    ])) {
                App::getDB()->update("user", [
                    "password" => hash('SHA512', $this->form->newPassword . $salt)
                        ], [
                    "login" => $this->user->login
                ]);
                Utils::addInfoMessage('Password changed successfully.');
            } else {
                Utils::addErrorMessage('Old password is incorrect.');
            }
        }
        App::getRouter()->forwardTo('profile');
    }

    private function generateGravatarUrl() {
        return $gravatarUrl = 'http://gravatar.com/avatar/' . md5($this->user->email) . '?d=monsterid&s=200';
    }

    public function generateView() {
        App::getSmarty()->assign('gravatar', $this->generateGravatarUrl());
        App::getSmarty()->assign('isInParty', $this->isInParty());
        App::getSmarty()->assign('partyName', $this->partyName);
        App::getSmarty()->assign('user', unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->display('ProfileView.tpl');
    }

}
