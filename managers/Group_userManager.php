<?php

class GroupManager extends AbstractManager
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

}