<?php 
declare(strict_types=1);
namespace Example\Entity;

use Example\Utility\Random;

Class Player
{
    protected $pid;
    protected $sid;
    protected $did;
    protected $platform;
    protected $version;
    protected $region;
    protected $gid;
    protected $aid;
    protected $fbid;
    protected $nick;
    protected $score;

    public function __construct()
    {
        $this->pid = null;
        $this->sid =  null;
        $this->did =  null;
        $this->platform =  null;
        $this->version =  null;
        $this->region = null;
        $this->fbid = null;
        $this->gid = null;
        $this->aid =  null;
        $this->score = null;
        $this->nick = null;
    }

    public function toArray() : array
    {
        $array =  [
            "device-id" => $this->did,
            "region" => $this->region,
            "platform" => $this->platform,
            "version" => $this->version,
            "player-id" => $this->pid,
            "session-id" => $this->sid
        ];

        if($this->fbid != null)
            $array['facebook-id'] = $this->fbid;
        if($this->gid != null)
            $array['google-id'] = $this->gid; 
        if($this->aid !=null)
            $array['apple-id'] = $this->gid; 
        if($this->score !=null)
            $array['score'] = $this->score; 
        if($this->nick !=null)
            $array['nick'] = $this->nick; 

        return $array;
    }

    public function createRandom(): Player
    {
        $this->pid = Random::generateCharacters(24);
        $this->sid = Random::generateCharacters(24);
        $this->did = Random::generateCharacters(10);
        $this->platform = rand(1,8);
        $this->version = $this->randomVersion();
        $this->region = "lt";
        return $this;
    }

    public function create(
        string $pid,
        string $sid,
        string $did,
        string $version,
        string $region,
        int $platform,
        string $fbid = null,
        string $gid = null,
        string $aid = null
    ): Player
    {
        $this->pid = $pid;
        $this->sid = $sid;
        $this->did = $did;
        $this->platform = $platform;
        $this->version = $version;
        $this->region = $region;
        $this->fbid = $fbid;
        $this->gid = $gid;
        $this->aid = $aid;
        return $this;
    }

    private function randomVersion() : string
    {
        $ran = rand(1,3);
        switch($ran)
        {
            case 1:{
                return "1.0";
                break;
            }
            case 2:{
                return "1.1";
                break;
            }
            case 3:{
                return "1.2";
                break;
            }
            default :{
                return "1.0";
            }
        }
    }

    public function getPlayerId()
    {
        return $this->pid;
    }

    public function getSessionId()
    {
        return $this->sid;
    }

    public function getDeviceId()
    {
        return $this->did;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getNick()
    {
        return $this->nick;
    }

    public function setPlayerId(string $pid)
    {
        $this->pid = $pid;
    }

    public function setSessionId(string $sid)
    {
         $this->sid = $sid;
    }

    public function setDeviceId(string $did)
    {
         $this->did = $did;
    }

    public function setPlatform(int $plat)
    {
         $this->platform = $plat;
    }

    public function setVersion(string $v)
    {
         $this->version = $v;
    }

    public function setRegion(string $reg)
    {
         $this->region = $reg;
    }

    public function setScore(int $score)
    {
        $this->score = $score;
    }

    public function setNick(string $nick)
    {
        $this->nick = $nick;
    }

    public function resetSessionId()
    {
        $this->sid = null;
    }
}