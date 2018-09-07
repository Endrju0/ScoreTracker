<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\ParamUtils;
use core\Validator;
use app\forms\PersonEditForm;

class PersonEditCtrl {

    private $form; //dane formularza

    public function __construct() {
        //stworzenie potrzebnych obiektów
        $this->form = new PersonEditForm();
    }

    public function validateSave() {
        //Pobranie id z walidacją czy istnieje (isset)
        $this->form->id = ParamUtils::getFromPost('id', true, 'Incorrect application call during validation.');

        // Używaj ParamUtils::getFromXXX('param',true,"...") do sprawdzenia czy parametr
        // został przesłany, -  czy ISTNIEJE (isset) - może być pusty, ale jest


        $v = new Validator();

        $this->form->login = $v->validateFromPost('login', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Enter the login.',
            'min_length' => 2,
            'max_length' => 30,
            'validator_message' => 'Login should have between 2 to 30 characters.'
        ]);

        // Używaj walidatora z konfiguracją "'required' => true" aby sprawdzić,
        // czy parametr NIE JEST PUSTY (!empty)

        $this->form->password = $v->validateFromPost('password', [
            'trim' => true,
            'required' => true,
            'required_message' => 'Enter the password.',
            'min_length' => 2,
            'max_length' => 45,
            'validator_message' => 'Password should have between 2 to 45 characters.'
        ]);
        $this->form->email = $v->validateFromPost('email', [
            'trim' => true,
            'required_message' => "Enter the email.",
            'max_length' => 45,
            'validator_message' => "Email should have between 2 to 45 characters."
        ]);
        return !App::getMessages()->isError();
    }

    //validacja danych przed wyswietleniem do edycji
    public function validateEdit() {
        //pobierz parametry na potrzeby wyswietlenia danych do edycji
        //z widoku listy osób (parametr jest wymagany)
        $this->form->id = ParamUtils::getFromCleanURL(1, true, 'Incorrect application call during validation.');
        return !App::getMessages()->isError();
    }

    public function action_personNew() {
        $this->generateView();
    }

    //wysiweltenie rekordu do edycji wskazanego parametrem 'id'
    public function action_personEdit() {
        // 1. walidacja id osoby do edycji
        if ($this->validateEdit()) {
            try {
                // 2. odczyt z bazy danych osoby o podanym ID (tylko jednego rekordu)
                $record = App::getDB()->get("user", "*", [
                    "id" => $this->form->id
                ]);
                // 2.1 jeśli osoba istnieje to wpisz dane do obiektu formularza
                $this->form->id = $record['id'];
                $this->form->login = $record['login'];
                $this->form->password = $record['password'];
                $this->form->email = $record['email'];
            } catch (\PDOException $e) {
                Utils::addErrorMessage('An unexpected error occurred during fetching values from database.');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }

        // 3. Wygenerowanie widoku
        $this->generateView();
    }

    public function action_personDelete() {
        // 1. walidacja id osoby do usuniecia
        if ($this->validateEdit()) {

            try {
                // 2. usunięcie rekordu
                App::getDB()->delete("user", [
                    "id" => $this->form->id
                ]);
                Utils::addInfoMessage('Person deleted successfully.');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('An unexpected error occurred during deleting values from database.');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }

        // 3. Przekierowanie na stronę listy osób
        App::getRouter()->forwardTo('personList');
    }

    public function action_personSave() {

        // 1. Walidacja danych formularza (z pobraniem)
        if ($this->validateSave()) {
            $this->form->joined = date('Y-m-d');
            // 2. Zapis danych w bazie
            try {
                //2.1 Nowy rekord
                if ($this->form->id == '') {
                    //sprawdź liczebność rekordów - nie pozwalaj przekroczyć 20
                    $count = App::getDB()->count("user");
                    if ($count <= 20) {
                        App::getDB()->insert("user", [
                            "login" => $this->form->login,
                            "password" => $this->form->password,
                            "email" => $this->form->email,
                            "role_id" => 3,
                            "joined" => $this->form->joined
                        ]);
                    } else { //za dużo rekordów
                        // Gdy za dużo rekordów to pozostań na stronie
                        Utils::addInfoMessage('Restriction: Too many records. To add new one, delete older record.');
                        $this->generateView(); //pozostań na stronie edycji
                        exit(); //zakończ przetwarzanie, aby nie dodać wiadomości o pomyślnym zapisie danych
                    }
                } else {
                    //2.2 Edycja rekordu o danym ID
                    App::getDB()->update("user", [
                        "login" => $this->form->login,
                        "password" => $this->form->password,
                        "email" => $this->form->email,
                            ], [
                        "id" => $this->form->id
                    ]);
                }
                Utils::addInfoMessage('Record saved successfully.');
            } catch (\PDOException $e) {
                Utils::addErrorMessage('An unexpected error occurred during saving values to the database.');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }

            // 3b. Po zapisie przejdź na stronę listy osób (w ramach tego samego żądania http)
            App::getRouter()->forwardTo('personList');
        } else {
            // 3c. Gdy błąd walidacji to pozostań na stronie
            $this->generateView();
        }
    }

    public function generateView() {
        App::getSmarty()->assign('form', $this->form); // dane formularza dla widoku
        App::getSmarty()->assign('user',unserialize(ParamUtils::getFromSession('user')));
        App::getSmarty()->display('PersonEdit.tpl');
    }

}
