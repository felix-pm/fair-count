<?php

class ExpenseManager extends AbstractManager
{

    public function __construct()
    {
        parent::__construct();
    }   

    public function findAll() : array
    {
        
        $query = $this->db->prepare('
            SELECT 
                expenses.id AS expense_id,
                expenses.title,
                expenses.amount,
                expenses.date,
                expenses.created_at AS expense_created_at,
                
                cat.id AS category_id,
                cat.label AS category_label,
                
                grp.id AS group_id,
                grp.name AS group_name,
                grp.created_by AS group_created_by,
                grp.created_at AS group_created_at,
                
                users.id AS payer_id,
                users.email,
                users.password,
                users.firstname,
                users.lastname,
                users.role

            FROM expenses
            JOIN categories AS cat ON expenses.category_id = cat.id
            JOIN `groups` AS grp ON expenses.group_id = grp.id
            JOIN users ON expenses.user_id = users.id
        ');

        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $expenses = [];

        foreach($results as $result) {

            // A. Création du Groupe
            $group = new Group(              
                $result['group_name'],
                $result['group_created_by'],
                $result['group_created_at'],
                $result['group_id']
            );
           
            $category = new Category(                
                $result['category_label'],
                $result['category_id']
            );
            
            $payer = new User(
                $result['email'],
                $result['password'],
                $result['firstname'],
                $result['lastname'],
                $result['role'],
                $result['payer_id']
            );    
            

            $exp = new Expense(                
                $result["title"],
                $result["amount"],
                $result["date"],
                $payer,
                $category,
                $group,
                $result["expense_created_at"],
                $result["expense_id"]
            );

            $expenses[] = $exp;
        }

        return $expenses;
    }

    public function create_expense(Expense $expense, array $participantIds):void
    {
        $expenseParticipantManager = new Expense_participantManager();
        $query = $this->db->prepare(
        'INSERT INTO expenses (title, amount, date, user_id, category_id, group_id, created_at) 
        VALUES (:title, :amount, :date, :user_id, :category_id, :group_id, :created_at)');
        $parameters = [
            "title" => $expense->getTitle(),
            "amount" => $expense->getAmount(),
            "date" => $expense->getDate(),
            "user_id" => $expense->getUser_id()->getId(),
            "category_id" => $expense->getCategory_id()->getId(),
            "group_id" => $expense->getGroup_id()->getId(),
            "created_at" => $expense->getCreated_at()
        ];
        $query->execute($parameters);

        $newExpenseId = (int)$this->db->lastInsertId();
        foreach ($participantIds as $userId) {
            // Appeler le Manager de liaison pour créer l'entrée
            $expenseParticipantManager->create($newExpenseId, $userId); 
        }

    }

}

?>

