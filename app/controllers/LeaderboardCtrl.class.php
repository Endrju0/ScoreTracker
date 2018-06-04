<?php

namespace app\controllers;

use core\App;
use core\Utils;
use core\SessionUtils;
use core\Validator;
use app\forms\LeaderboardForm;

class LeaderboardCtrl {
  private $form;

  public function __construct() {
    $this->form = new LeaderboardForm();
  }

  public function action_leaderboard() {
    $this->checkParty();
    $this->generateView();
  }

  private function loadUserId() {
    $this->form->uid = SessionUtils::load('sessionId', true);
  }
  public function checkParty() {
    $this->loadUserId();
    $pid = App::getDB()->get("user", "party_id", [
      "id" => $this->form->uid
    ]);
    if($pid == NULL) {
      try {
          $this->form->partyList = App::getDB()->select("party", [
              "id",
              "name"
            ]);
      } catch (\PDOException $e) {
          Utils::addErrorMessage('Wystąpił błąd podczas sprawdzania party');
          if (App::getConf()->debug)
              Utils::addErrorMessage($e->getMessage());
      }
    } else {
      $this->form->partyName = App::getDB()->get("party", "name", [
        "id" => $pid
      ]);
    }
  }

  public function action_joinParty() {
    $this->loadUserId();
    $v = new Validator();

    $this->form->joinParty = $v->validateFromPost('party', [
        'trim' => true,
        'required' => true
    ]);

    if(App::getDB()->count("party", [
      	"name" => $this->form->joinParty
      ]) > 0 ) {
      $partyid = App::getDB()->get("party", "id", [
        			"name" => $this->form->joinParty
        		 ]);
      App::getDB()->update("user", [
                        "party_id" => $partyid,
                            ], [
                        "id" => $this->form->uid
                    ]);
    } else {
      Utils::addErrorMessage('Party o takiej nazwie nie istnieje!');
    }
    $this->generateView();
  }


  public function generateView() {
      App::getSmarty()->assign('partyName', $this->form->partyName);
      App::getSmarty()->assign('partyList', $this->form->partyList);
      App::getSmarty()->display('LeaderboardView.tpl');
  }
}