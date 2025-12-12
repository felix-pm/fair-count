<?php

class Group_user
{
    public function __construct(private int $id,private Group  $group_id,private User $user_id)
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


    public function getGroup_id() : Group
    {
        return $this->group_id;
    }

    public function setGroup_id($group_id) : Group
    {
        $this->group_id = $group_id;

        return $this;
    }

    
    public function getUser_id() : User
    {
        return $this->user_id;
    }

    public function setUser_id($user_id) : User
    {
        $this->user_id = $user_id;

        return $this;
    }
}

?>