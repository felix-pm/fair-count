<?php

class ExpenseManager extends AbstractManager
{

    public function __construct()
    {
        parent::__construct();
    }


    public function findParticipantsByExpenseId(int $expenseId) : array
    {
        $query = $this->db->prepare('
            SELECT expense_participants.id as part_id, 
                   expense_participants.expense_id,
                   users.* FROM expense_participants
            JOIN users ON expense_participants.user_id = users.id
            WHERE expense_participants.expense_id = :expense_id
        ');
        
        $query->execute(["expense_id" => $expenseId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $participants = [];
        
        foreach($results as $item) {
            // 1. On crée le User (le participant)
            $participantUser = new User(
                $item['email'],
                $item['password'],
                $item['firstname'],
                $item['lastname'],
                $item['role'],
                $item['id']
            );

            // 2. On crée le lien (Expense_participant)
            // Note: On passe juste l'ID de la dépense, pas l'objet entier, pour éviter la boucle infinie
            $participants[] = new Expense_participant(
                $item['part_id'],
                $item['expense_id'],
                $participantUser
            );
        }

        return $participants;
    }

    // Dans managers/ExpenseManager.php

    public function findAll() : array
    {
        // 1. La requête se concentre sur la Dépense et le PAYEUR (users)
        // J'utilise des alias (AS) pour bien différencier les IDs
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
            JOIN users ON expenses.user_id = users.id  -- ICI on joint le PAYEUR
        ');

        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $expenses = [];

        foreach($results as $result) {

            // A. Création du Groupe
            $group = new Group(
                $result['group_id'],
                $result['group_name'],
                $result['group_created_by'],
                $result['group_created_at']
            );

            // B. Création de la Catégorie
            $category = new Category(
                $result['category_id'],
                $result['category_label']
            );

            // C. Création du Payeur (User)
            // Attention à bien utiliser les données qui viennent de la table users jointe ci-dessus
            // Si ta classe User n'a pas l'ID en dernier paramètre, adapte l'ordre ici
            $payer = new User(
                $result['email'],
                $result['password'],
                $result['firstname'],
                $result['lastname'],
                $result['role'],
                $result['payer_id']
            );    
            

            $exp = new Expense(
                $result["expense_id"], // Ton constructeur semble attendre ça en premier
                $result["title"],
                $result["amount"],
                $result["date"],
                $result["payer_id"], // L'ID du payeur
                $category,
                $group,
                $result["expense_created_at"]
            );

            $expenses[] = $exp;
        }

        return $expenses;
    }

}

?>

