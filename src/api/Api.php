<?php 
declare(strict_types=1);
namespace Example\Api;

Class Api
{
    /**
     * @var Server
     */
    protected $server;

    public function __construct()
    {
        $this->server = new Server();
    }
    
    /**
     * Client commands
     */
    public function openSession(array $params = array(
        "name" => "",
        "platform" => 1,
        "version" => "",
        "region" => ""
    )) : string
    {
        if(isset($params['name'])) $name = $params['name'];
        else return $this->server->reject("Missing required parameter name!",100);
        if(isset($params['platform'])) $platform = $params['platform'];
        else return $this->server->reject("Missing required parameter platform!",101);
        if(isset($params['version'])) $version = $params['version'];
        else return $this->server->reject("Missing required parameter version!",102);
        if(isset($params['region'])) $region = $params['region'];
        else return $this->server->reject("Missing required parameter region!",103);
        if(isset($params['fbid'])) $fbid = $params['fbid'];
        else $fbid = null;
        if(isset($params['gid'])) $gid = $params['gid'];
        else $gid = null;
        if(isset($params['aid'])) $aid = $params['aid'];
        else $aid = null;
        
        return $this->server->onOpenSessionRequest($name, $platform, $version, $region, $fbid, $gid, $aid);
    }

    public function refresh(array $params = array(
        "player-id" => "",
        "session-id" => ""
    )) : string
    {
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",106);
        if(isset($params['session-id'])) $sessionId = $params['session-id'];
        else return $this->server->reject("Missing required parameter session-id!",107);
        return $this->server->onRefresh($playerId,$sessionId);
    }

    public function closeSession(array $params = array(
        "player-id" => "",
        "session-id" => ""
    )) : string
    {
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",106);
        if(isset($params['session-id'])) $sessionId = $params['session-id'];
        else return $this->server->reject("Missing required parameter session-id!",107);
        return $this->server->onCloseSession($playerId,$sessionId);
    }


    public function registerLeaderboard(array $params = array(
        "player-id" => "",
        "session-id" => "",
        "leaderboard-id" => ""
    )) : string
    {
        if(isset($params['leaderboard-id'])) $lid = $params['leaderboard-id'];
        else return $this->server->reject("Missing required parameter leaderboard-id!",105);
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",106);
        if(isset($params['session-id'])) $sessionId = $params['session-id'];
        else return $this->server->reject("Missing required parameter session-id!",107);
        return $this->server->onRegisterLeaderboard($lid,$playerId, $sessionId);
    }

    /**
     * Admin commands
     */
    public function createLeaderboard(array $params = array(
        "flag" => "",
        "audience" => array(),
        "name" => ""
    )) : string
    {
        if(isset($params['flag'])) $flag = $params['flag'];
        else return $this->server->reject("Missing required parameter flag!",102);
        if(isset($params['audience'])) $audience = $params['audience'];
        else return $this->server->reject("Missing required parameter audience!",103);
        if(isset($params['name'])) $name = $params['name'];
        else return $this->server->reject("Missing required parameter name!",111);

        return $this->server->onCreateLeaderboard($flag,$audience,$name);
    }

    public function getAllLeaderboard() : string
    {
        return $this->server->onGetAllLeaderboard();
    }

    public function getLeaderboard(array $params = array(
        "leaderboard-id" => ""
    )) : string
    {
        if(isset($params['leaderboard-id'])) $lid = $params['leaderboard-id'];
        else return $this->server->reject("Missing required parameter leaderboard-id!",104);
        return $this->server->onGetLeaderboard($lid);
    }

    public function deleteLeaderboard(array $params = array(
        "leaderboard-id" => ""
    )) : string
    {
        if(isset($params['leaderboard-id'])) $lid = $params['leaderboard-id'];
        else return $this->server->reject("Missing required parameter leaderboard-id!",104);
        return $this->server->onDeleteLeaderboard($lid);
    }

    
    public function getPlayer(array $params = array(
        "player-id" => ""
    )) : string
    {
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",104);
        return $this->server->onGetPlayer($playerId);
    }

    public function deletePlayer(array $params = array(
        "player-id" => ""
    )) : string
    {
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",104);
        return $this->server->onDeletePlayer($playerId);
    }

    public function getAllPlayer() : string
    {
        return $this->server->onGetAllPlayer();
    }

    public function setNick(array $params = array(
        "player-id" => "",
        "nickname" => ""
    )) : string
    {
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",104);
        if(isset($params['nickname'])) $nickname = $params['nickname'];
        else return $this->server->reject("Missing required parameter nickname!",111);

        return $this->server->onSetNick($playerId,$nickname);
    }

    public function increaseScore(array $params = array(
        "player-id" => "",
        "score" => 0
    )) : string
    {
        if(isset($params['player-id'])) $playerId = $params['player-id'];
        else return $this->server->reject("Missing required parameter player-id!",104);
        if(isset($params['score'])) $score = $params['score'];
        else return $this->server->reject("Missing required parameter score!",111);

        return $this->server->onIncreaseScore($playerId,$score);
    }
}