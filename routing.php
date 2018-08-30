<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('login'); // akcja/ścieżka domyślna
App::getRouter()->setLoginRoute('login'); // akcja/ścieżka na potrzeby logowania (przekierowanie, gdy nie ma dostępu)

Utils::addRoute('personList',    'PersonListCtrl', ['admin']);
Utils::addRoute('personListPart','PersonListCtrl', ['admin']);
Utils::addRoute('loginShow',     'LoginCtrl');
Utils::addRoute('login',         'LoginCtrl');
Utils::addRoute('logout',        'LoginCtrl');
Utils::addRoute('register',		 'RegisterCtrl');
Utils::addRoute('leaderboard',  'LeaderboardCtrl',	['user','moderator','admin']);
Utils::addRoute('joinParty',  'LeaderboardCtrl',	['user','moderator','admin']);
Utils::addRoute('createParty',  'LeaderboardCtrl',	['user','moderator','admin']);
Utils::addRoute('incWins',  'LeaderboardCtrl',	['moderator','admin']);
Utils::addRoute('incAmount',  'LeaderboardCtrl',	['moderator','admin']);
Utils::addRoute('decWins',  'LeaderboardCtrl',	['moderator','admin']);
Utils::addRoute('decAmount',  'LeaderboardCtrl',	['moderator','admin']);
Utils::addRoute('seasonManagement',  'SeasonManagementCtrl',	['moderator','admin']);
Utils::addRoute('newSeason',  'SeasonManagementCtrl',	['moderator','admin']);
Utils::addRoute('setActiveSeason',  'SeasonManagementCtrl',	['moderator','admin']);
Utils::addRoute('deleteSeason',  'SeasonManagementCtrl',	['moderator','admin']);
Utils::addRoute('profile',  'ProfileCtrl',	['user','moderator','admin']);
Utils::addRoute('leaveParty',  'ProfileCtrl',	['user','moderator','admin']);
Utils::addRoute('personNew',     'PersonEditCtrl',	['admin']);
Utils::addRoute('personEdit',    'PersonEditCtrl',	['admin']);
Utils::addRoute('personSave',    'PersonEditCtrl',	['admin']);
Utils::addRoute('personDelete',  'PersonEditCtrl',	['admin']);
