<?php

class game
{

    public function __construct(
        private int $id, 
        private string $name, 
        private string $date, 
        private ?Team $team1,
        private ?Team $team2,
        private ?int $winner
    )
    {

    }

    public function getName(): string 
    { 
        return $this->name; 
    }
    public function getDate(): string { 
        return $this->date; 
    }
    
    public function getTeam1(): Team { 
        return $this->team1; 
    }
    public function getTeam2(): Team {
         return $this->team2; 
    }

    public function getWinner()
    {
            return $this->winner;
    }
}

?>