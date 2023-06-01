<?php 
declare(strict_types=1);

namespace Example\Repository;

use Example\Entity\Player;
use Example\Exceptions\PlayerNotFoundException;

class PlayerRepository
{
     /**
     * @var Player[]
     */
    protected $players;

    public function __construct()
    {
        $this->players = [
            (new Player())->createRandom(),
            (new Player())->createRandom(),
            (new Player())->createRandom(),
            (new Player())->createRandom(),
            (new Player())->createRandom(),
        ];
    }
    
    /**
     * @throws PlayerNotFoundException
     */
    public function getPlayer(string $pid) : Player
    {
        foreach($this->players as $player)
        {
            if($player->getPlayerId() == $pid)
                return $player;
        }
        throw new PlayerNotFoundException();
    }

    public function getPlayers() : array
    {
        return $this->players;
    }

    public function insert(Player $player) : void
    {
        array_push($this->players, $player);
    }
     
    /**
     * @throws PlayerNotFoundException
     */
    public function remove(string $pid) : bool
    {
        foreach($this->players as $id => $player)
        {
            if($player->getPlayerId() == $pid){
                unset($this->players[$id]);
                return true;
            }
        }
        throw new PlayerNotFoundException();
    }

    public function toArray() : array
    {
        $array = array();
        foreach($this->players as $player)
        {
            array_push($array, $player->toArray());
        }
        return $array;
    }
}