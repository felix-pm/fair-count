<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $parameters = [

        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($result as $item)
        {
            $user = new User($item["id"], $item["firstname"], $item["lastname"], $item["email"], $item["password"], $item["created_at"]);
            $users[] = $user;
        }

        return $users;
    }

    public function findById(int $id) : ? User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new User($item["id"], $item["firstname"], $item["lastname"], $item["email"], $item["password"], $item["created_at"]);
        }

        return null;
    }

    public function findByEmail(string $email) : ? User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            "email" => $email
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new User($item["id"], $item["firstname"], $item["lastname"], $item["email"], $item["password"], $item["created_at"]);
        }

        return null;
    }

    public function create_user(User $user) : void
        {
            $query = $this->db->prepare('INSERT INTO users (firstname, lastname, email, password, created_at) VALUES (:firstname, :lastname, :email, :password, :created_at)');
            
            $parameters = [
                "firstname" => $user->getfirstname(),
                "lastname" => $user->getlastname(), 
                "email"  => $user->getEmail(),
                "password"     => $user->getPassword(),
                "created_at"  => $user->getCreate_at()
            ];
            $query->execute($parameters);
        }

    public function update(User $user) : void
    {
        $query = $this->db->prepare('UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, password = :password, created_at = :created_at WHERE id = :id');;
        $parameters = [
            "id" => $user->getId(),
            "firstname" => $user->getfirstname(),
            "lastname" => $user->getlastname(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "created_at" => $user->getCreate_at()
        ];
        $query->execute($parameters);
    }

    public function delete(User $user) : void
    {
        $query = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $parameters = [
            "id" => $user->getId()
        ];
        $query->execute($parameters);
    }
}
