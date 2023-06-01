<?php
declare(strict_types=1);

namespace Example\Api;

use Example\Entity\Leaderboard;
use Example\Entity\Player;
use Example\Exceptions\LeaderboardNotFoundException;
use Example\Exceptions\PlayerNotFoundException;
use Example\Repository\LeaderboardRepository;
use Example\Repository\PlayerRepository;
use Example\Utility\Random;

class Server
{
    /**
     * @var PlayerRepository
     */
    protected $repo;
    /**
     * @var LeaderboardRepository
     */
    protected $repoLboard;

    public function __construct()
    {
        $this->repo = new PlayerRepository();
        $this->repoLboard = new LeaderboardRepository();
    }

    public function onOpenSessionRequest(
        $name,
        $platform,
        $version,
        $region,
        $fbid = null,
        $gid = null,
        $aid = null
    ) : string
    {

        if(!isset($name) || $name == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument name!", "code" => 500))]]
        ));
        else if(strlen($name) <= 5) return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Name must not be shorter than 5 characters!", "code" => 501))]]
        ));

        if(!isset($platform) || !is_int($platform)) return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument platform!", "code" => 502))]]
        ));

        if(!($platform == 1 || $platform == 2 || $platform == 3 || $platform == 4 || 
            $platform == 5 || $platform == 6 || $platform == 7 || $platform == 8)) 
        return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Unknown platform!", "code" => 504))]]
        ));

        if(!isset($version) || $version == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument version!", "code" => 505))]]
        ));
        if(!($version == "1.0" || $version == "1.1" || $version == "1.2"))
        return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Unknown version!", "code" => 506))]]
        ));

        if(!isset($region) || $region == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument region!", "code" => 507))]]
        ));
        if(!($region == "lt" || $region == "en"  || $region == "us" || $region == "fr" ))
        return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Unknown region!", "code" => 508))]]
        ));

        $player = (new Player())->create(
            Random::generateCharacters(24),
            Random::generateCharacters(24),
            $name,
            $version,
            $region,
            $platform,
            $fbid,
            $gid,
            $aid
        );
        $this->repo->insert($player);
        $repos = $this->getLeaderboardsByPlayer($player);
        $this->sid = Random::generateCharacters(24);
        return $this->resolve(array(
            "device-id" => $player->getDeviceId(),
            "region" => $player->getRegion(),
            "platform" => $player->getPlatform(),
            "version" => $player->getVersion(),
            "player-id" => $player->getPlayerId(),
            "session-id" => $player->getSessionId(),
            "leaderboards" => $repos->toSimpleArray()
        ));
    }

    public function onRefresh(
        $playerId,
        $sessionId
    ) : string
    {
        if(!isset($playerId) || $playerId == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument player id!", "code" => 509))]]
        ));

        if(!isset($sessionId) || $sessionId == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument session id!", "code" => 510))]]
        ));

        try{
            $player = $this->repo->getPlayer($playerId);
        }
        catch(PlayerNotFoundException $ex)
        {
            return json_encode(array(
                "status" => "fail",
                "data" => ["ccrd" => [array("error" => array("msg" => "Player with such id does not exist on the server!", "code" => 511))]]
            ));
        }   

        if($player->getSessionId() == null || $player->getSessionId() != $sessionId) return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Player session is wrong!", "code" => 512))]]
        ));

        $repos = $this->getLeaderboardsByPlayer($player);
        $this->sid = Random::generateCharacters(24);
        return $this->resolve(array("leaderboards" => $repos->toSimpleArray()));
    }

    public function onCloseSession(
        $playerId,
        $sessionId
    ) : string
    {
        if(!isset($playerId) || $playerId == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument player id!", "code" => 509))]]
        ));

        if(!isset($sessionId) || $sessionId == "") return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Missing required argument session id!", "code" => 510))]]
        ));
        
        try{
            $player = $this->repo->getPlayer($playerId);
        }
        catch(PlayerNotFoundException $ex)
        {
            return json_encode(array(
                "status" => "fail",
                "data" => ["ccrd" => [array("error" => array("msg" => "Player with such id does not exist on the server!", "code" => 511))]]
            ));
        }   

        if($player->getSessionId() == null || $player->getSessionId() != $sessionId) return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => "Player session is wrong!", "code" => 512))]]
        ));

        $response = array();
        $response['status'] = "ok";
        $player->resetSessionId();
        $response['data'] = [array()];
        return json_encode($response);
    }

    public function onGetPlayer(
        $playerId
    ) : string
    {
        if(!isset($playerId) || $playerId == "") return $this->reject("Missing required argument player id", 509);
        try{
            $player = $this->repo->getPlayer($playerId);
        }
        catch(PlayerNotFoundException $ex)
        {
            return $this->reject("Player with such id does not exist on the server!",511);
        }   
        return $this->resolve($player->toArray());
    }

    public function onDeletePlayer(
        $playerId
    ) : string
    {
        if(!isset($playerId) || $playerId == "") return $this->reject("Missing required argument player id", 509);
        try{
            $this->repo->remove($playerId);
        }
        catch(PlayerNotFoundException $ex)
        {
            return $this->reject("Player with such id does not exist on the server!",511);
        }   
        return $this->resolve();
    }

    public function onGetAllPlayer() : string
    {
        return $this->resolve(array("players" => $this->repo->toArray()));
    }

    public function onRegisterLeaderboard(
        $lid,
        $playerId,
        $sessionId
    ) : string
    {
        if(!isset($playerId) || $playerId == "") 
            return $this->reject("Missing required argument player id!", 509);

        if(!isset($lid) || $lid == "") 
            return $this->reject("Missing required argument leaderboard id!", 513);

        if(!isset($sessionId) || $sessionId == "") 
            return $this->reject("Missing required argument session id!", 510);
        try{
            $player = $this->repo->getPlayer($playerId);
        }
        catch(PlayerNotFoundException $ex)
        {
            return $this->reject("Player with such id does not exist on the server!", 511);
        }   

        if($player->getSessionId() == null || $player->getSessionId() != $sessionId)
            return $this->reject("Player session is wrong!", 512);

        try{
            $leaderboard = $this->repoLboard->getLeaderboard($lid);
        }
        catch(LeaderboardNotFoundException $ex)
        {
            return $this->reject("Leaderboard with such id does not exist on the server!", 514);
        }

        $leaderboard->addPlayer($player);
        return $this->resolve();
    }

    public function onGetAllLeaderboard() : string
    {
        return $this->resolve(array("leaderboards" => $this->repoLboard->toArray()));
    }

    public function onGetLeaderboard(
        $lid
    ) : string
    {
        if(!isset($lid) || $lid == "") return $this->reject("Missing required argument leaderboard id", 515);

        try{
            $leaderboard = $this->repoLboard->getLeaderboard($lid);
        }
        catch(LeaderboardNotFoundException $ex)
        {
            return $this->reject("Leaderboard with such id does not exist on the server!",516);
        }   

        return $this->resolve($leaderboard->toArray());
    }

    public function onDeleteLeaderboard(
        $lid
    ) : string
    {
        if(!isset($lid) || $lid == "") return $this->reject("Missing required argument leaderboard id", 515);

        try{
            $this->repoLboard->remove($lid);
        }
        catch(LeaderboardNotFoundException $ex)
        {
            return $this->reject("Leaderboard with such id does not exist on the server!",516);
        }   

        return $this->resolve();
    }

    public function onIncreaseScore(
        $pid,
        $value
    ) : string
    {
        if(!isset($pid) || $pid == "") return $this->reject("Missing required argument player id", 509);
        if(!isset($value) || $value == "") return $this->reject("Missing required argument value", 516);
        if(!is_int($value)) return $this->reject("Value must be int", 517);
        try{
            $player = $this->repo->getPlayer($pid);
        }
        catch(PlayerNotFoundException $ex)
        {
            return $this->reject("Player with such id does not exist on the server!",511);
        }  
        if($player->getScore() == null)
            $player->setScore($value);
        else {
            $player->setScore($player->getScore() + $value);
        } 
        return $this->resolve(array("score" => $player->getScore() + $value));
    }

    public function onSetNick(
        $pid,
        $nickname
    ) : string
    {
        if(!isset($pid) || $pid == "") return $this->reject("Missing required argument player id", 509);
        if(!isset($nickname) || $nickname == "") return $this->reject("Missing required argument nickname", 518);
        if(!is_string($nickname)) return $this->reject("Nickname must be string", 519);
        try{
            $player = $this->repo->getPlayer($pid);
        }
        catch(PlayerNotFoundException $ex)
        {
            return $this->reject("Player with such id does not exist on the server!",511);
        }  
        $player->setNick($nickname);
        return $this->resolve();
    }

    public function onCreateLeaderboard(
        $flag,
        $audience,
        $name
    ) : string
    {
        if(!isset($flag) || $flag == "") return $this->reject("Missing required argument flag", 520);
        if(!isset($name) || $name == "") return $this->reject("Missing required argument name", 528);
        if(!isset($audience) || $audience == "") return $this->reject("Missing required argument audience", 521);
        if(!is_array($audience)) return $this->reject("Audience must be array", 522);
        try{
            $this->repoLboard->insert((new Leaderboard())->create(new PlayerRepository(),$flag,$audience,$name));
        }
        catch(\Exception $ex)
        {
            return $this->reject($ex->getMessage(),511);
        }  
        return $this->resolve();
    }

    public function reject(string $reason, int $code) : string
    {
        return json_encode(array(
            "status" => "fail",
            "data" => ["ccrd" => [array("error" => array("msg" => $reason, "code" => $code))]]
        ));
    }

    public function resolve(array $data = array()) : string
    {
        $response = array();
        $response['status'] = "ok";
        $response['data'] = [$data];
        return json_encode($response);
    }

    private function getLeaderboardsByPlayer(Player $player) : LeaderboardRepository
    {
        $array = new LeaderboardRepository();
        foreach($this->repoLboard->getLeaderboards() as $leaderboard)
        {
            $audience = $leaderboard->getAudience();
            if(isset($audience['players']))
                if(array_search($player->getPlayerId(),$audience['players']))
                    $array->insert($leaderboard);
            else if(empty($audience))
                $array->insert($leaderboard);
        }
        return $array;
    }
}