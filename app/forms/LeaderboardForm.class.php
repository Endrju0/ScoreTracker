<?php

namespace app\forms;

class LeaderboardForm {
  public $newPartyName; //nazwa nowego party pobrana z formularza
  public $partyName; //nazwa party z bazy po id party
  public $partyList; //list wszystkich dostępnych party
  public $joinParty; //nazwa party z formularza do dołączenia do istniejącego
  public $trackerList;
}
