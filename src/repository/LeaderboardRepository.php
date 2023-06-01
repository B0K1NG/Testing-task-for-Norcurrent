<?php 
declare(strict_types=1);

namespace Example\Repository;

use Example\Entity\Leaderboard;
use Example\Exceptions\LeaderboardNotFoundException;

class LeaderboardRepository
{
     /**
     * @var Leaderboard[]
     */
    protected $leaderboards;

    public function __construct()
    {
        $this->leaderboards = [
            (new Leaderboard())->createRandom()
        ];
    }
    
    /**
     * @throws LeaderboardNotFoundException
     */
    public function getLeaderboard(string $id) : Leaderboard
    {
        foreach($this->leaderboards as $leaderboard)
        {
            if($leaderboard->getId() == $id)
                return $leaderboard;
        }
        throw new LeaderboardNotFoundException();
    }

    public function getLeaderboards() : array
    {
        return $this->leaderboards;
    }

    public function insert(Leaderboard $leaderboard) : void
    {
        array_push($this->leaderboards, $leaderboard);
    }
     
    /**
     * @throws LeaderboardNotFoundException
     */
    public function remove(string $lid) : bool
    {
        foreach($this->leaderboards as $id => $leaderboard)
        {
            if($leaderboard->getId() == $lid){
                unset($this->leaderboards[$id]);
                return true;
            }
        }
        throw new LeaderboardNotFoundException();
    }

    public function toArray() : array
    {
        $array = array();
        foreach($this->leaderboards as $leaderboard)
        {
            array_push($array, $leaderboard->toArray());
        }
        return $array;
    }

    public function toSimpleArray() : array
    {
        $array = array();
        foreach($this->leaderboards as $leaderboard)
        {
            array_push($array, $leaderboard->toSimpleArray());
        }
        return $array;
    }
}