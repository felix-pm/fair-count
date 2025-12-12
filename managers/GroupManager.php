<?php

class GroupManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM `groups`');
        $parameters = [

        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $groups = [];

        foreach($result as $item)
        {
            $group = new Group($item["id"], $item["name"], $item["created_by"], $item["created_at"]);
            $groups[] = $group;
        }

        return $groups;
    }

    public function findById(int $id) : ? Group
    {
        $query = $this->db->prepare('SELECT * FROM `groups` WHERE id = :id');
        $parameters = [
            "id" => $id
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new Group($item["id"], $item["name"], $item["created_by"], $item["created_at"]);
        }

        return null;
    }

    public function findByName(string $name) : ? Group
    {
        $query = $this->db->prepare('SELECT * FROM `groups` WHERE name = :name');
        $parameters = [
            "name" => $name
        ];
        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item)
        {
            return new Group($item["id"], $item["name"], $item["created_by"], $item["created_at"]);
        }

        return null;
    }

    public function create(Group $group) : void
    {
        $query = $this->db->prepare('INSERT INTO `groups` (name, created_by, created_at) VALUES (:name, :created_by, :created_at)');
        $parameters = [
            "name" => $group->getName(),
            "created_by" => $group->getCreated_by(),
            "created_at" => $group->getCreated_at()
        ];
        $query->execute($parameters);
    }

    public function update(Group $group) : void
    {
        $query = $this->db->prepare('UPDATE `groups` SET name = :name, created_by = :created_by, created_at = :created_at WHERE id = :id');;
        $parameters = [
            "id" => $group->getId(),
            "name" => $group->getName(),
            "created_by" => $group->getCreated_by(),
            "created_at" => $group->getCreated_at()
        ];
        $query->execute($parameters);
    }

    public function delete(Group $group) : void
    {
        $query = $this->db->prepare('DELETE FROM `groups` WHERE id = :id');;
        $parameters = [
            "id" => $group->getId()
        ];
        $query->execute($parameters);
    }
}
