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
            $category = new Category($item["label"], $item["id"]);
            $categorys[] = $category;
        }

        return $categorys;
    }

    public function findByName(string $label) : Category
    {
        $query = $this->db->prepare('SELECT * FROM categories WHERE label = :label');
        $parameters = [
            'label'=> $label

        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        return new Category($result["label"], $result["id"]);
    }
}
