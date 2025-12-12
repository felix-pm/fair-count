<?php

class ExpenseManager extends AbstractManager
{

    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {
        $query = $this->db->prepare(
        'SELECT * FROM expenses 
        JOIN expense_participants ON expenses.id=expense_participants.expense_id,
        JOIN users ON expense_participants.user_id=users.id        
        JOIN groups ON expenses.group_id=groups.id
        JOIN categories ON expenses.id=categories.id
          ');
        
        $parameters = [
        
        ];

        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $expense = [];

        foreach($results as $result) {            

            $group = new Group(
                $result['id'],
                $result['name'],
                $result['created_by'],
                $result['created_at']               
            );

            $category= new Category(
                $result['id'],
                $result['label']              
            );
            

            $user= new User(
                $result["id"],
                $result["email"],
                $result["password"],                
                $result["firstname"],  
                $result["lastname"],
                $result["role"]

            );

            $exp=new Expense(
                $result["id"],
                $result["title"],
                $result["amount"],
                $result["date"],
                $result["date"],
                $category,
                $group,               
                $result["created_at"]
                
            );

            $expense[]=$exp;
        }

        return $performance;
    }

}

?>