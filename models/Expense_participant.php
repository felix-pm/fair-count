<?php

class Expense_participant
{

    public function __construct(private int $id, private int $expense_id, private User $user_id)
    {

    }     

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
   
    public function getExpense_id()
    {
        return $this->expense_id;
    }

    public function setExpense_id($expense_id)
    {
        $this->expense_id = $expense_id;

        return $this;
    }
    
    public function getUser_id():User
    {
        return $this->user_id;
    }
   
    public function setUser_id($user_id):self
    {
        $this->user_id = $user_id;

        return $this;
    }
}

?>