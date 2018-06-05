<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('login'); // akcja/ścieżka domyślna
App::getRouter()->setLoginRoute('login'); // akcja/ścieżka na potrzeby logowania (przekierowanie, gdy nie ma dostępu)

Utils::addRoute('personList',    'PersonListCtrl');
Utils::addRoute('personListPart','PersonListCtrl');
Utils::addRoute('loginShow',     'LoginCtrl');
Utils::addRoute('login',         'LoginCtrl');
Utils::addRoute('logout',        'LoginCtrl');
Utils::addRoute('register',		 'RegisterCtrl');
Utils::addRoute('leaderboard',  'LeaderboardCtrl',	['user','moderator','admin']);
Utils::addRoute('joinParty',  'LeaderboardCtrl',	['user','moderator','admin']);
Utils::addRoute('createParty',  'LeaderboardCtrl',	['user','moderator','admin']);
Utils::addRoute('personNew',     'PersonEditCtrl',	['admin']);
Utils::addRoute('personEdit',    'PersonEditCtrl',	['user','admin']);
Utils::addRoute('personSave',    'PersonEditCtrl',	['user','admin']);
Utils::addRoute('personDelete',  'PersonEditCtrl',	['admin']);
