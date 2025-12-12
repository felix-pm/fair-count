<?php

class CategoryManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {
        $query = $this->db->prepare('SELECT * FROM categories');
        $parameters = [

        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $categorys = [];

        foreach($result as $item)
        {
            $category = new Category($item["id"], $item["label"]);
            $categorys[] = $category;
        }

        return $categorys;
    }
}
