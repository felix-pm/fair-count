<?php

class Expense_participant
{
    // On change le type de $expense_id : Expense -> int
    public function __construct(private int $expense_id, private int $user_id, private ?int $id = null)
    {

    }     

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
   
    // On met à jour le Getter pour renvoyer un int
    public function getExpense_id() : int
    {
        return $this->expense_id;
    }

    // On met à jour le Setter pour accepter un int
    public function setExpense_id($expense_id)
    {
        $this->expense_id = $expense_id;
        return $this;
    }
    
    public function getUser_id()
    {
        return $this->user_id;
    }
   
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
}
?>