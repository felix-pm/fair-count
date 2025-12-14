<?php

class Expense_participantManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array
    {

        $query = $this->db->prepare('SELECT 
        expenses.id AS expenses_id,
        expenses.title AS expenses_title,
        expenses.amount AS expenses_amount,
        expenses.date AS expenses_date,
        expenses.user_id AS expenses_user_id,
        expenses.category_id AS expenses_category_id,
        expenses.group_id AS expenses_group_id,
        expenses.created_at AS expenses_created_at,

        users.id AS user_id,
        users.username AS user_username,
        users.email AS user_email,
        users.password AS user_password,
        users.created_at AS user_created_at,

        expense_participants.id AS expense_participant_id,
        expense_participants.user_id AS expense_participant_user_id,
        expense_participants.expense_id AS expense_participant_expense_id

        FROM expense_participants 
        JOIN expenses ON expense_participant_expense_id = expenses_id
        JOIN users ON expense_participant_user_id, = user_id');

        $parameters = [

        ];

        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);


        $expense_participant = [];
        foreach($result as $result)
        {
            $expense = new Expense($result["expenses_id"], $result["expenses_title"], $result["expenses_amount"], $result["expenses_date"], $result["expenses_user_id"], null, $result["expenses_group_id"], $result["expenses_created_at"]);

            $user = new User($result["user_id"], $result["user_username"], $result["user_email"], $result["user_password"], $result["user_created_at"]);
            
            $expense_participant[] = new Group_user($result["expense_participant_id"], $expense->getId(), $user->getId());
        }

        return $expense_participant;

    }

    public function create(int $expenseId, $userid) 
    {
        $query = $this->db->prepare(
        'INSERT INTO expense_participants (expense_id, user_id) 
        VALUES (:expense_id, :user_id)');

        $parameters = [
            "expense_id"=>$expenseId,
            "user_id"=>$userid

        ];

        $query->execute($parameters);
        


    }

}