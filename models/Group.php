<?php

class Group
{
    public function __construct(int $id, string $name, string $created_by, Datetime $created_at)
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


    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    public function getCreated_by()
    {
        return $this->created_by;
    }

    public function setCreated_by($created_by)
    {
        $this->created_by = $created_by;

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