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
        'SELECT expenses.id AS expense_id,
            expenses.title,
            expenses.amount,
            expenses.date,
            expenses.user_id AS userid,
            expenses.category_id AS categoryid,
            expenses.group_id AS expense_group_fk,
            expenses.created_at AS createdat,
            
           
            categories.id AS category_id,
            categories.label,

            
            groups.id AS groupid,
            groups.name AS group_name,
            groups.created_by,
            groups.created_at AS group_created_at,
           
            expense_participants.id AS participant_id,
            expense_participants.user_id AS user_id1,
            
            
            users.email,
            users.password,
            users.firstname,
            users.lastname,
            users.role
            
        FROM expenses
        JOIN expense_participants ON expenses.id = expense_participants.expense_id
        JOIN users ON expense_participants.user_id = users.id
        JOIN groups ON expenses.group_id = groups.id
        JOIN categories ON expenses.category_id = categories.id 
     
          ');
        
        $parameters = [
        
        ];

        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $expense = [];

        foreach($results as $result) {            

            $group = new Group(
                $result['groupid'],
                $result['group_name'],
                $result['created_by'],
                $result['group_created_at']               
            );

            $user= new User(
                $result['email'],
                $result['password'],
                $result['firstname'],
                $result['lastname'],
                $result['role'],
            );

            $expense_participant = new Expense_participant(
                $result['participant_id'],
                $result['expense_id'],
                $user
            );

            $category= new Category(
                $result['category_id'],
                $result['label']              
            );           
            

            $exp=new Expense(
                $expense_participant,
                $result["title"],
                $result["amount"],
                $result["date"],
                $result["userid"],                
                $category,
                $group,               
                $result["createdat"]                
            );

            $expense[]=$exp;
        }

        return $expense;
    }

}

?>

