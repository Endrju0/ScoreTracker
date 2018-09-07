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
            'required_message' => 'Enter the login.',
            'min_length' => 2,
            'max_length' => 30,
            'validator_message' => 'Login should have between 2 to 30 characters.'
        ]);

        $this->form->reg_password = $v->validateFromPost('reg_password', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Enter the password.',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Password should have between 2 to 45 characters.'
        ]);
        $this->form->reg_email = $v->validateFromPost('reg_email', [
            'trim' => true,
            'required' => true,
            'email' => true,
            'required_message' => "Enter the email.",
            'max_length' => 45,
            'validator_message' => "Email should have between 2 to 45 characters."
        ]);
        if (App::getDB()->has("user", [
          "OR" => [
            "login" => $this->form->reg_login,
            "email" => $this->form->reg_email
          ]
        ])) Utils::addErrorMessage('Username or email is already taken!');

        return !App::getMessages()->isError();
    }

    public function action_register() {
        if ($this->validate()) {
            $this->form->reg_registration_date = date('Y-m-d');
            try {
                App::getDB()->insert("user", [
                    "login" => $this->form->reg_login,
                    "password" => $this->form->reg_password,
                    "email" => $this->form->reg_email,
                    "role_id" => 3,
                    "registration_date" => $this->form->reg_registration_date
                ]);
                Utils::addInfoMessage('Account created successfully.');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('An unexpected error occurred during saving values to the database.');
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
