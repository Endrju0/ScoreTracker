<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\RoleUtils;
use core\ParamUtils;
use core\Browser;
use core\SessionUtils;
use app\forms\LoginForm;

class LoginCtrl {

    private $form;

    public function __construct() {
        //stworzenie potrzebnych obiektów
        $this->form = new LoginForm();
    }

    public function validate() {
        $this->form->login = ParamUtils::getFromRequest('login');
        $this->form->pass = ParamUtils::getFromRequest('pass');

        //nie ma sensu walidować dalej, gdy brak parametrów
        if (!isset($this->form->login))
            return false;

        // sprawdzenie, czy potrzebne wartości zostały przekazane
        if (empty($this->form->login)) {
            Utils::addErrorMessage('Nie podano loginu');
        }
        if (empty($this->form->pass)) {
            Utils::addErrorMessage('Nie podano hasła');
        }

        //nie ma sensu walidować dalej, gdy brak wartości
        if (App::getMessages()->isError())
            return false;

        // sprawdzenie, czy dane logowania poprawne
        // (takie informacje najczęściej przechowuje się w bazie danych)
        if ($this->form->login == App::getDB()->get("user", "login", [
                    "password" => $this->form->pass,
                    "role_id" => 1
                ])) {
            RoleUtils::addRole('admin');
            $this->storeId();
        } else if ($this->form->login == App::getDB()->get("user", "login", [
                    "password" => $this->form->pass,
                    "role_id" => 2
                ])) {
            RoleUtils::addRole('moderator');
            $this->storeId();
        } else if ($this->form->login == App::getDB()->get("user", "login", [
                    "password" => $this->form->pass,
                    "role_id" => 3
                ])) {
            RoleUtils::addRole('user');
            $this->storeId();
        } else if (App::getDB()->count("user", ["login" => $this->form->login])) {
            $user_id = App::getDB()->get("user", "id", [
                "login" => $this->form->login
            ]);

            App::getDB()->insert("session", [
                "date" => date("Y-m-d H:i:s"),
                "browser" => Browser::exactBrowserName(),
                "ip" => Browser::getIpAddress(),
                "user_id" => $user_id
            ]);
            Utils::addErrorMessage('Niepoprawny login lub hasło');
        } else {
            Utils::addErrorMessage('Niepoprawny login lub hasło');
        }

        return !App::getMessages()->isError();
    }

    public function action_loginShow() {
        $this->generateView();
    }

    public function action_login() {
        if ($this->validate()) {
            //zalogowany => przekieruj na główną akcję (z przekazaniem messages przez sesję)
            Utils::addErrorMessage('Poprawnie zalogowano do systemu');
            App::getRouter()->redirectTo("personList");
        } else {
            //niezalogowany => pozostań na stronie logowania
            $this->generateView();
        }
    }

    public function action_logout() {
        // 1. zakończenie sesji
        session_destroy();
        // 2. idź na stronę główną - system automatycznie przekieruje do strony logowania
        App::getRouter()->redirectTo('personList');
    }

    public function storeId() {
        $id = App::getDB()->get("user", "id", [
            "login" => $this->form->login,
            "password" => $this->form->pass
        ]);
        SessionUtils::store('sessionId', $id);
    }

    public function generateView() {
        App::getSmarty()->assign('form', $this->form); // dane formularza do widoku
        App::getSmarty()->display('LoginView.tpl');
    }

}
