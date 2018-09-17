<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\RoleUtils;
use core\ParamUtils;
use core\Browser;
use core\SessionUtils;
use app\transfer\User;
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

        if (App::getDB()->has("session", [
                    "AND" => [
                        "browser" => Browser::exactBrowserName(),
                        "ip" => Browser::getIpAddress()
                    ]
                ])) {
            if (App::getDB()->count("session", [
                        "AND" => [
                            "browser" => Browser::exactBrowserName(),
                            "ip" => Browser::getIpAddress(),
                            "date[<>]" => [date('Y-m-d H:i:s', strtotime('-5 minutes')), date("Y-m-d H:i:s")]
                        ]
                    ]) > 4) {
                Utils::addErrorMessage('Too many login attempts. Wait few minutes.');
            }
        }

        //nie ma sensu walidować dalej, gdy brak parametrów
        if (!isset($this->form->login))
            return false;

        // sprawdzenie, czy potrzebne wartości zostały przekazane
        if (empty($this->form->login)) {
            Utils::addErrorMessage('Enter the login.');
        }
        if (empty($this->form->pass)) {
            Utils::addErrorMessage('Enter the password.');
        }

        //nie ma sensu walidować dalej, gdy brak wartości
        if (App::getMessages()->isError())
            return false;

        /* Sprawdzenie czy zmienne z formularza istnieją w bazie oraz czy pasują do siebie,
          Jeśli wszystko jest poprawne to przydzielona jest odpowiednia rola użytkownikowi */
        try {
            $salt = 'ScoreTrackerProject';
            $passwordHash = hash('SHA512', $this->form->pass . $salt);
            //sprawdzenie czy w bazie jest użytkownik z danym hasłem z formularza
            if (App::getDB()->has("user", [
                        "AND" => [
                            "OR" => [
                                "login" => $this->form->login,
                                "email" => $this->form->login
                            ],
                            "password" => $passwordHash
                        ]
                    ])) {
                //pobranie danych użytkownika po weryfikacji danych logowania
                $profile = App::getDB()->get("user", [
                    "id",
                    "login",
                    "email",
                    "role_id",
                    "party_id",
                    "last_login"
                        ], [
                    "login" => $this->form->login,
                    "password" => $passwordHash
                ]);

                //pobranie nazwy roli z tabeli 'role' po kluczu 'role_id' z tabeli 'user'
                $role = App::getDB()->get("role", [
                    "role"
                        ], [
                    "id" => $profile['role_id'],
                ]);

                //stworzenie obiektu user, który będzie przechowywany w sesji
                $user = new User(
                        $profile['id'], $profile['login'], $profile['email'], $profile['role_id'], $profile['party_id'], $role['role'], $profile['last_login']);
                SessionUtils::store('user', serialize($user));

                /* Zaktualizowanie informacji o ostatnim logowaniu
                  (dopiero po utworzeniu obiektu w sesji, bo chodzi o ostatnie nie aktualne) */
                App::getDB()->update("user", [
                    "last_login" => date("Y-m-d H:i:s")
                        ], [
                    "id" => $profile['id']
                ]);

                RoleUtils::addRole($user->role);
            } else {
                //informacja do bazy o nieudanym logowaniu
                App::getDB()->insert("session", [
                    "date" => date("Y-m-d H:i:s"),
                    "browser" => Browser::exactBrowserName(),
                    "ip" => Browser::getIpAddress()
                ]);
                Utils::addErrorMessage('Incorrect login or password.');
            }
        } catch (\PDOException $e) {
            Utils::addErrorMessage('An unexpected error occurred during saving records to the database.');
            if (App::getConf()->debug)
                Utils::addErrorMessage($e->getMessage());
        }

        return !App::getMessages()->isError();
    }

    public function action_loginShow() {
        $this->generateView();
    }

    public function action_login() {
        if ($this->validate()) {
            //zalogowany => przekieruj na główną akcję
            App::getRouter()->redirectTo("leaderboard");
        } else {
            //niezalogowany => pozostań na stronie logowania
            $this->generateView();
        }
    }

    public function action_logout() {
        //zakończenie sesji
        session_destroy();
        //przekierowanie na strone logowania
        App::getRouter()->redirectTo('login');
    }

    public function generateView() {
        App::getSmarty()->assign('form', $this->form); // dane formularza do widoku
        App::getSmarty()->display('LoginView.tpl');
    }

}
