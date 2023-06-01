## Requirements for environment

* Composer 2.*
* Phpunit 9.5
* Php 8.1

## Documentation

### Installation

Install by running:

```
composer install
```

```
composer dumpautoload -o
```

```
./vendor/bin/phpunit tests
```

### Players

* To create player use openSession command;
* Versions stand for game version, currently there are versions : "1.0","1.1" and "1.2";
* Platform number stands for platform, for example 1 - stands for android, 2 - ios, 3 - mac_os, 4- windows store, 5- windows_phone, 6-amazon, 7-browser, 8 - pc;
* To renew session, player must send open-session or refresh;

#### Player parameters
* Available platforms : [1,2,3,4,5,6,7,8]
* Available versions : ["1.0","1.1","1.2"]
* Available regions : ["lt", "en", "us", "fr"]

#### Player commands
* openSession - Creates new player on server;
* refresh - Renews player session id on server;
* closeSession - Removes player session id from player, and player cannot send commands, unless obtains new session id;
* getPlayer - Retrieves all player data from server;
* getAllPlayer - Retrieves all players data from server;
* deletePlayer - Deletes player from server;
* increaseScore - Increases player score;
* setNickname - Sets player nickname;
* registerLeaderboard - Registers player to leaderboard;

### Leaderboards

* All leaderboards are returned with open-session,refresh commands;
* Player must register to leaderboard in order to participate in it;

#### Leaderboards parameters

* flag - "score";
* audience - "{}" for all players;
* audience - "["player-id-1","player-id-2"]" for filtering players;

#### Leaderboards commands

* getLeaderboard - Gets leaderboard data from server;
* getAllLeaderboard - Gets all leaderboards data from server;
* createLeaderboard - Creates new leaderboard on server;
* deleteLeaderboard - Deletes leaderboard from server;

## Assignment 

Write tests for following testcases

### Test Case 1 
Check results on changing player's nickname. 

* Platforms: all.
* Versions: all.
* Regions: 'us'.
 
### Test Case 2
Check results when the player's nickname is changed while playing a tournament.

* Platforms: 5, 8.
* Versions: all.
* Regions: all.
 
 
### Test Case 3 
Check results when deleting leaderboards while playing a tournament.

* Platforms: 5, 8.
* Versions:  1.0.
* Regions: 'us'.
 
 
### Test Case 4 
Check whether player token updated when refreshing player.

* Platforms: all.
* Versions: all.
* Region: all.
 
 
### Test Case 5 
Check whether player token exists when closing session.

* Platforms: 3.
* Versions: 1.2.
* Region: 'fr'.
 
### Test Case 6
Check is it possible to create player when platform doesn't exist.
