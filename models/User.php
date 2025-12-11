<?php

class User
{

    public function __construct(private int $id, private string $username, private string $email, private string $password, private Datetime $create_at)
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
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getCreate_at()
    {
        return $this->create_at;
    }

    public function setCreate_at($create_at)
    {
        $this->create_at = $create_at;

        return $this;
    }
}

?>