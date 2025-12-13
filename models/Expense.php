<?php

class Expense
{
public function __construct(private string $title, private int $amount, private string $date, private User $user_id, private Category $category_id, private Group $group_id, private string $created_at, private ?int $id = null)
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

    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getUser_id(): User
    {
        return $this->user_id;
    }

    public function setUser_id($user_id):self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCategory_id(): Category
    {
        return $this->category_id;
    }

    public function setCategory_id($category_id):self 
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getGroup_id(): Group
    {
        return $this->group_id;
    }

    public function setGroup_id($group_id): self
    {
        $this->group_id = $group_id;

        return $this;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }
}

?>