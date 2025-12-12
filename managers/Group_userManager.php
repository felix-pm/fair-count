<?php

class GroupManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {

        $query = $this->db->prepare('SELECT * FROM group_users 
        JOIN groups ON group_users.group_id = groups.id 
        JOIN users ON group_users.user_id = users.id');

        $parameters = [

        ];

        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $result)
        {
            $group = new Group($result["id"], $item["name"], $item["created_by"], $item["created_at"]);
            $groups[] = $group;
        }

        return $groups;

    }

}