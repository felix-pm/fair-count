<?php

class Reimnursement
{
    public function __construct(int $id, int $amount, int $from_user_id, int $to_user_id, int $group_id, Datetime $created_at)
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


    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }


    public function getFrom_user_id()
    {
        return $this->from_user_id;
    }

    public function setFrom_user_id($from_user_id)
    {
        $this->from_user_id = $from_user_id;

        return $this;
    }


    public function getTo_user_id()
    {
        return $this->to_user_id;
    }

    public function setTo_user_id($to_user_id)
    {
        $this->to_user_id = $to_user_id;

        return $this;
    }


    public function getGroup_id()
    {
        return $this->group_id;
    }

    public function setGroup_id($group_id)
    {
        $this->group_id = $group_id;

        return $this;
    }


    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }
}

?>