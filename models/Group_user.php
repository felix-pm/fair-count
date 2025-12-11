<?php

class Group_user
{
    public function __construct(int $id, string $group_id, string $user_id)
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


    public function getGroup_id()
    {
        return $this->group_id;
    }

    public function setGroup_id($group_id)
    {
        $this->group_id = $group_id;

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