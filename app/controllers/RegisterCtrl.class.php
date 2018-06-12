<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\Validator;
use app\forms\RegisterForm;

class RegisterCtrl {

    private $form;

    public function __construct() {
        $this->form = new RegisterForm();
    }

    public function validate() {
        //Pobranie id z walidacją czy istnieje (isset)
        $v = new Validator();

        $this->form->reg_login = $v->validateFromPost('reg_login', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Podaj login',
            'min_length' => 2,
            'max_length' => 30,
            'validator_message' => 'Login powinno mieć od 2 do 30 znaków'
        ]);

        $this->form->reg_password = $v->validateFromPost('reg_password', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Podaj hasło',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Hasło powinno mieć od 2 do 45 znaków'
        ]);
        $this->form->reg_email = $v->validateFromPost('reg_email', [
            'trim' => true,
            'required_message' => "Wprowadź email",
            'max_length' => 45,
            'validator_message' => "Email powinen mieć od 2 do 45 znaków"
        ]);
        return !App::getMessages()->isError();
    }

    public function action_register() {
        if ($this->validate()) {
            $this->form->reg_joined = date('Y-m-d');
            try {
                App::getDB()->insert("user", [
                    "login" => $this->form->reg_login,
                    "password" => $this->form->reg_password,
                    "email" => $this->form->reg_email,
                    "role_id" => 3,
                    "joined" => $this->form->reg_joined
                ]);
                Utils::addInfoMessage('Pomyślnie utworzono konto');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił nieoczekiwany błąd podczas zapisu rekordu');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }

            App::getRouter()->forwardTo('login');
        } else {
            // 3c. Gdy błąd walidacji to pozostań na stronie
            $this->generateView();
        }
    }

    public function generateView() {
        App::getSmarty()->assign('form', $this->form); // dane formularza do widoku
        App::getSmarty()->display('LoginView.tpl');
    }

}
