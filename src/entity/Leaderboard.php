<?php 
declare(strict_types=1);

namespace Example\Entity;
use Example\Repository\PlayerRepository;
use Example\Utility\Random;
use Exception;

class Leaderboard
{
    protected $id;
    /**
     * @var PlayerRepository
     */
    protected $players;
    protected $flag;
    protected $audience;
    protected $name;

    public function __construct()
    {
        $this->id = null;
        $this->players = null;
        $this->flag = null;
        $this->audience = null;
        $this->name = null;
    }

    public function create(
        PlayerRepository $players,
        string $flag,
        array $audience,
        string $name
    ) : Leaderboard
    {
        $this->id = Random::generateCharacters(24);
        $this->players = $players;
        $this->flag = $flag;
        if(!$this->checkFlag()) throw new \Exception(ucfirst($flag). " is not a valid flag name!");
        $this->audience = $audience;
        if(!$this->checkAudience()) throw new \Exception("Audience is not valid!");
        $this->name = $name;
        return $this;
    }

    public function createRandom() : Leaderboard
    {
        $this->id = Random::generateCharacters(24);
        $this->players = new PlayerRepository();
        $this->players->insert((new Player())->createRandom());
        $this->players->insert((new Player())->createRandom());
        $this->players->insert((new Player())->createRandom());
        $this->players->insert((new Player())->createRandom());
        $this->players->insert((new Player())->createRandom());
        $this->flag = "score";
        $this->audience = array();
        $this->name = "Leaderboard-".Random::generateNumbers(5);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getFlag()
    {
        return $this->flag;
    }

    public function getAudience()
    {
        return $this->audience;
    }

    public function toArray() : array
    {
        $array = array();
        $methodToCall = "get". ucfirst($this->flag);
        foreach($this->players->getPlayers() as $player){
            array_push($array, [
                "id" => $player->getPlayerId(),
                "score" => $player->$methodToCall(),
                "nick" => $player->getNick()
            ]);
        }
        return [
            "id" => $this->id,
            "participants" => $array,
            "flag" => $this->flag,
            "name" => $this->name,
            "audience" => $this->audience
        ];
    }

    public function toSimpleArray() : array
    {
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }

    public function addPlayer(Player $player) : void
    {
        $this->players->insert($player);
    }

    public function removePlayer(string $pid) : void
    {
        $this->players->remove($pid);
    }

    private function checkFlag() : bool
    {
        $methodToCall = "get". ucfirst($this->flag);
        return method_exists((new Player()),$methodToCall);
    }

    private function checkAudience() : bool
    {
        if(!is_array($this->audience)) return false;
        else if(empty($this->audience)) return true;
        else if(isset($this->audience['players']) && is_array($this->audience['players']) && !empty($this->audience['players'])) {
            foreach($this->audience['players'] as $id => $data)
            {
                if(!is_string($data))return false;
            }
            return true;
        }
        return false;
    }
}