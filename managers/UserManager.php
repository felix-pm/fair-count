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
            $user = new User($item["id"], $item["firstname"], $item["lastname"], $item["email"], $item["password"], $item["role"]);
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
            return new User($item["id"], $item["firstname"], $item["lastname"], $item["email"], $item["password"], $item["role"]);
        }

        return null;
    }

    // Dans managers/UserManager.php

    public function findByEmail(string $email): ?User
        {
            $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
            $query->execute([':email' => $email]);
            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                // ATTENTION : L'ordre ici doit être IDENTIQUE à User.php
                return new User(
                    $data['email'],        // 1. email
                    $data['password'],     // 2. password
                    $data['firstname'],    // 3. firstname (vérifiez le nom de votre colonne en base, ex: first_name ?)
                    $data['lastname'],     // 4. lastname (vérifiez le nom de votre colonne en base, ex: last_name ?)
                    $data['role']          // 5. role
                );
            }

            return null;
        }

    public function create_user(User $user)
        {
            $query = $this->db->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (:firstname, :lastname, :email, :password, :role)");
            $parameters = [
                ':firstname' => $user->getFirstName(), // Attention à la majuscule selon votre User.php
                ':lastname'  => $user->getLastName(),
                ':email'     => $user->getEmail(),
                ':password'  => $user->getPassword(),
                ':role'      => $user->getRole()
            ];

            // 3. On exécute
            $query->execute($parameters);
        }

    public function update(User $user) : void
    {
        $query = $this->db->prepare('UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, password = :password role = :role WHERE id = :id');;
        $parameters = [
            "firstname" => $user->getfirstname(),
            "lastname" => $user->getlastname(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
            "role" => $user->getrole()
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
