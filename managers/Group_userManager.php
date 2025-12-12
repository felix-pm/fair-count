<?php

class Group_userManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {

        $query = $this->db->prepare('SELECT 
        groups.id AS group_id,
        groups.name AS group_name,
        groups.created_by AS group_creator_id,
        groups.created_at AS group_created_at,

        users.id AS user_id,
        users.username AS user_username,
        users.email AS user_email,
        users.password AS user_password,
        users.created_at AS user_created_at,

        group_users.id AS group_user_id,
        group_users.user_id AS group_user_user_id,
        group_users.group_id AS group_user_group_id

        FROM group_users 
        JOIN groups ON group_users.group_id = groups.id 
        JOIN users ON group_users.user_id = users.id');

        $parameters = [

        ];

        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);


        $group_user = [];
        foreach($result as $result)
        {
            $group = new Group($result["group_id"], $result["group_name"], $result["group_creator_id"], $result["group_created_at"]);

            $user = new User($result["user_id"], $result["user_username"], $result["user_email"], $result["user_password"], $result["user_created_at"]);
            
            $group_user[] = new Group_user($result["group_user_id"], $group->getId(), $user->getId());
        }

        return $group_user;

    }

    // Dans managers/Group_userManager.php

    public function findUsersByGroupId(int $groupId) : array
    {
        // On sélectionne toutes les infos de l'utilisateur
        // en passant par la table de liaison group_users
        $query = $this->db->prepare('
            SELECT users.* FROM users 
            JOIN group_users ON users.id = group_users.user_id 
            WHERE group_users.group_id = :group_id
        ');

        $parameters = [
            "group_id" => $groupId
        ];

        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($result as $item)
        {
            // On recrée des objets User (ordre des paramètres basé sur ton UserManager)
            $users[] = new User(
                $item["email"],
                $item["password"], 
                $item["firstname"], 
                $item["lastname"], 
                $item["role"],
                $item["id"]
            );
        }

        return $users;
    }

    // Dans managers/Group_userManager.php

    public function findGroupsByUserId(int $userId) : array
    {
        // On sélectionne toutes les infos du groupe (groups.*)
        // en passant par la table de liaison (group_users)
        $query = $this->db->prepare('
            SELECT `groups`.* FROM `groups` 
            JOIN group_users ON groups.id = group_users.group_id 
            WHERE group_users.user_id = :user_id
        ');

        $parameters = [
            "user_id" => $userId
        ];

        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $groups = [];

        foreach($result as $item)
        {
            $groups[] = new Group(
                $item["id"], 
                $item["name"], 
                $item["created_by"], 
                $item["created_at"]
            );
        }

        return $groups;
    }

}