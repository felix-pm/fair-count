<?php

class User
{

    public function __construct(private string $email, private string $password, private string $firstname, private string $lastname, private string $role, private ?int $id = NULL)
    {

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
    
    public function getfirstname()
    {
        return $this->firstname;
    }
    
    public function setfirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getlastname()
    {
        return $this->lastname;
    }
    
    public function setlastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }


    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
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
}

?>