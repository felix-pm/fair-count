<?php

class UserController extends AbstractController
{
    
    // FONCTION POUR L'ADMIN
    public function create_user() :void {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN')
        {
            $this->redirect('index.php?route=login');
        }
        else {
            $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if( (empty($_POST["email"])) || (empty($_POST["password"]) )|| (empty($_POST["firstName"])) || (empty($_POST["lastName"] )) || (empty($_POST["confirmPassword"]) ) || (empty($_POST["role"]) ))
            {  
                $errors[] = "veuillez remplir tout les champs !";
            }

            //condition de lancement de l'erreur pour le if ci-dessous
            $ctrl = new UserManager;
            $verif_email = $ctrl->findByEmail($_POST["email"]);

            //findAll qui permet d'aller récupérer le password dans le else ci-dessous
            $datas = $ctrl->findAll();

            //si l'utilisateur n'existe pas une erreur est lancé
            if($verif_email != null)
            {
                $errors[] = "L'utilisateur existe déjà";
            }
            //si l'utilisateur existe il faut récupérer son password

            
            //si le password entré dans le formulaire correspond a celui de la base de donnée il n'y a pas d'erreur
            if(($_POST["password"]) === ($_POST["confirmPassword"]))
            {   
                $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
            }
            else
            {
                $errors[] = "Mot de passe incorrect";
            }
            
            
            if(empty($errors))
            {
                $new_user = new User($_POST["firstName"], $_POST["lastName"], $_POST["email"], $hashedPassword, $_POST["role"]);
                $ctrl->create_user($new_user); //mettre dans le if empty(errors) avec le $new_user
                $this->render('admin/users/create_user.html.twig', []);
            }
            else
            {
                $this->render('admin/users/create_user.html.twig', ['errors' => $errors]);
            }
            
        }
        
        }
    }

    public function update_user() : void {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN')
        {
            $this->redirect('index.php?route=login');
        }
        else {

            $ctrl = new UserManager;
            $datas = $ctrl->findById($_GET["id"]);
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $update_user = new User($_POST["firstName"], $_POST["lastName"], $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT), $_POST["role"], $datas->getId());
                $ctrl->update($update_user);
            }
            $this->render('admin/users/update_user.html.twig', ['datas' => $datas]);
        }
    }

    public function delete_user() : void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN')
        {
            $this->redirect('index.php?route=login');
        }
        else
        {
            $this->redirect("index.php?route=list_admin");
        }
    }

    public function list_admin() : void //montre la liste des user de toute l'appli
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN')
        {
            $this->redirect('index.php?route=login');
        }
        else
        {
            $ctrl = new UserManager;
            $datas = $ctrl->findAll();
            $this->render('admin/users/list_admin.html.twig', ['datas' => $datas]);
        }
    }

    public function show_user() : void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN')
        {
            $this->redirect('index.php?route=login');
        }
        else
        {
            $id = $_GET["id"];
            $ctrl = new UserManager;
            $datas = $ctrl->findById($id);
            $this->render('admin/users/show.html.twig', ["datas" => $datas]);
        }

    }

    public function profile() :void
    {
        if(isset($_SESSION["firstname"]) && isset($_SESSION["lastname"]) && isset($_SESSION["email"]) && isset($_SESSION["role"]) && isset($_SESSION["id"]))
        {
            if($_SESSION["role"] === "ADMIN")
            {
                $this->redirect('index.php?route=list_admin');
            }
            else
            {
                $this->render('member/profile.html.twig', []);
            }
        }
        else
        {
            $this->render('auth/login.html.twig', []);
        }
    }

    public function create_group() :void {
        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'ADMIN')
        {
            $this->redirect('index.php?route=list_admin');
        }
        else {
            $errors = [];
            $ctrlUser = new UserManager;
            $ctrlGroup = new GroupManager;
            $ctrlGroupUser = new Group_userManager;
            $allusers=$ctrlUser->findAll();
            $currentUserId = $_SESSION['id'];

            

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {           

            if( (empty($_POST["group"]))) {  
                $errors[] = "veuillez remplir le nom du groupe !";
            }            
            
            $verif_group = $ctrlGroup->findByName($_POST["group"]);
                        
            if($verif_group != null)
            {
                $errors[] = "Le groupe existe déjà";
            }

            $participantsIds = [
                $currentUserId, 
                (int) ($_POST["participant_1"] ?? 0),
                (int) ($_POST["participant_2"] ?? 0),
                (int) ($_POST["participant_3"] ?? 0),
                (int) ($_POST["participant_4"] ?? 0),
                ];

                $finalarrays=array_unique(array_filter($participantsIds));
            
            if(empty($errors))
            {                
                $currentDate = date('Y-m-d H:i:s');
                $new_group = new Group($_POST["group"], $currentUserId , $currentDate);
                $newGroupId=$ctrlGroup->createandgetId($new_group);

                
                
                foreach ($finalarrays as $finalarray){

                    if ((int)$finalarray > 0){


                        $ctrlGroupUser->create_groupe_user((int)$finalarray,$newGroupId);  
                
                    }
                }

                $this->redirect('index.php?route=home');
                return;
            }            
            
        }

        $this->render('member/create_group.html.twig', [
                    'users' => $allusers,
                    'errors' => $errors,
                    'current_user_id' => $currentUserId
                ]);       
        
        }
    }

    public function update_group() : void {
        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'ADMIN')
        {
            $this->redirect('index.php?route=list_admin');
        }
        else {

            $ctrl = new GroupManager;
            $datas = $ctrl->findById($_GET["id"]);
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $update_group = new Group($_POST["name"], $_POST["created_by"], $_POST["created_at"], $datas->getId());
                $ctrl->update($update_group);
            }
            $this->render('member/update_group.html.twig', ['datas' => $datas]);
        }
    }

    public function delete_group() : void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'ADMIN')
        {
            $this->redirect('index.php?route=list_admin');
        }
        else {

            $ctrl = new GroupManager;
            $datas = $ctrl->findById($_GET["id"]);
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $delete_group = new Group($_POST["name"], $_POST["created_by"], $_POST["created_at"], $datas->getId());
                $ctrl->delete($delete_group);
            }
            $this->render('member/update_group.html.twig', ['datas' => $datas]);
        }
    }

    // public function expenses() : void //page des dépenses
    // {
    //     $ctrl = new CategoryManager;
    //     $categorys = $ctrl->findAll();
    //     $ctrl2 = new Group_userManager;
    //     $name_user = $ctrl2->findAll()
    //     $this->render("member/expenses.html.twig", ["categorys" => $categorys]);
    // }

    // Dans controllers/UserController.php

    public function expenses() : void 
    {
        if (!isset($_GET['id'])) {
            $this->redirect('index.php?route=home'); 
        }
        else{           
            
        $errors = [];
        $groupId = (int) $_GET['id'];
        $ctrlCategory = new CategoryManager;
        $ctrlGroupUser = new Group_userManager;        
        $ctrlExpense = new ExpenseManager;
        $ctrlUser = new UserManager;
        $ctrlGroup = new GroupManager; 
        $categorys = $ctrlCategory->findAll();        
        $groupUsers = $ctrlGroupUser->findUsersByGroupId($groupId); //va cherche tous les user affilié au groupes

        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            if( (empty($_POST["expense_name"])) || (empty($_POST["category"])) || (empty($_POST["expense_amount"])) || (empty($_POST["user_id"])) || (empty($_POST["expense_date"]))) {  
                $errors[] = "Veuillez remplir tous les champs !";
            } 

            if(!isset($error)){
               
                $user = $ctrlUser->findById($_POST["user_id"]);
                $category = $ctrlCategory->findByName($_POST["category"]);
                $group = $ctrlGroup->findById($groupId);
                $currentDate=date('Y-m-d H:i:s');

                $participantIds = [];
        
        
                foreach ($groupUsers as $groupMember) {
            
                $participantIds[] = $groupMember->getId();
                }

                $Expense = new Expense($_POST["expense_name"], $_POST["expense_amount"], $_POST["expense_date"],$user,$category,$group,$currentDate);
                $ctrlExpense->create_expense($Expense,$participantIds);

                $this->redirect("index.php?route=affichage_expenses&id=$groupId", []);
                return;
        
            }
            $this->render("index.php?route=expenses&id=$groupId", [$error]);
        }
        
        
        

        $this->render("member/expenses.html.twig", [
            "categorys" => $categorys,            
            "groupUsers" => $groupUsers            
        ]);
        }
        }

    

    public function affichage_expenses() : void 
    {
        if (!isset($_GET['id'])) {
            $this->redirect('index.php?route=home'); 
        }
        
        $groupId = (int) $_GET['id'];

        $ctrlCategory = new CategoryManager;
        $categorys = $ctrlCategory->findAll();              

        $ctrlExpense = new ExpenseManager;
        $expenses = $ctrlExpense->findAll();        

        $this->render("member/affichage_expenses.html.twig", [
            "categorys" => $categorys,            
            "expenses" => $expenses,
            "groups" => $groupId
        ]);
        
    }

    public function home()
    {
        if (!isset($_SESSION['id'])) {
            $this->redirect('index.php?route=login');
        }

        $userId = $_SESSION['id'];

        $manager = new Group_userManager();
        $myGroups = $manager->findGroupsByUserId($userId);

        

        return $this->render('home/home.html.twig', [
            "groups" => $myGroups            
    ]);
    }








    
    
    public function reimbursement() : void 
    {
        // 1. Vérification de sécurité : a-t-on un ID de groupe ?
        if (!isset($_GET['id'])) {
            $this->redirect('index.php?route=home');
        }

        $groupId = (int) $_GET['id'];

        // 2. Initialisation du Manager
        // Note : Votre ReimbursementManager demande PDO dans son constructeur
        // Si votre AbstractController a une propriété $this->db, on l'utilise.
        // Sinon, il faudra récupérer l'instance de la BDD comme vous le faites ailleurs.
        // Je suppose ici que AbstractController ou AbstractManager gère la connexion.
        
        // ATTENTION : Dans votre code fourni, ReimbursementManager attend (PDO $pdo).
        // Si vous utilisez un singleton pour la DB, adaptez la ligne ci-dessous :
        // $db = Database::getInstance(); ou $this->db si accessible
        
        // Pour cet exemple, je pars du principe que AbstractController permet d'accéder à la DB
        // Il faudra peut-être adapter cette ligne selon votre connexion :
        $rm = new ReimbursementManager(); 
        // Si vous n'avez pas accès à la variable $db ici, instanciez-la.
        // Exemple classique : $rm = new ReimbursementManager($this->db);
        
        // 3. Appel de l'algorithme "Qui doit combien"
        $reimbursementPlan = $rm->getReimbursementPlan($groupId);

        // 4. Envoi des données à la vue
        $this->render("member/reimbursement.html.twig", [
            "plan" => $reimbursementPlan,
            "group_id" => $groupId
        ]);
    }

public function validate_reimbursement() : void
{
    // 1. On vérifie que le formulaire a bien envoyé des données (POST)
    if (isset($_POST['from_id'], $_POST['to_id'], $_POST['amount'], $_POST['group_id'])) {
        
        // 2. On récupère et nettoie les données
        $fromId = (int) $_POST['from_id'];
        $toId = (int) $_POST['to_id'];
        $amount = (float) $_POST['amount'];
        $groupId = (int) $_POST['group_id'];

        // 3. On appelle le Manager pour insérer le remboursement en BDD
        $rm = new ReimbursementManager();
        
        // Tu dois t'assurer d'avoir créé cette méthode 'createReimbursement' 
        // dans ton Manager (comme vu juste avant)
        $rm->createReimbursement($fromId, $toId, $amount, $groupId);

        // 4. C'est fini ! On redirige l'utilisateur vers la page d'affichage.
        // L'utilisateur verra la liste mise à jour.
        $this->redirect("index.php?route=reimbursement&id=" . $groupId);
    
    } else {
        // Si quelqu'un essaie de lancer cette fonction sans formulaire
        $this->redirect('index.php?route=home');
    }
}

    
}
