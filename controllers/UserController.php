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
                $ctrl->create($new_user); //mettre dans le if empty(errors) avec le $new_user
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if( (empty($_POST["name"])) || (empty($_POST["participant"]) )) {  
                $errors[] = "veuillez remplir tout les champs !";
            }
            $ctrl = new GroupManager;
            $verif_group = $ctrl->findByName($_POST["name"]); 
            $datas = $ctrl->findAll();
            if($verif_group != null)
            {
                $errors[] = "Le groupe existe déjà";
            }
            
            if(empty($errors))
            {
                $new_group = new Group($_POST["name"], $_POST["participants"], $_POST["date"]);
                $ctrl->create_group($new_group); 
                $this->render('member/create_group.html.twig', []);
            }
            else
            {
                $this->render('member/create_group.html.twig', ['errors' => $errors]);
            }
            
        }
        
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
                $update_user = new Group($datas->getId(), $_POST["name"], $_POST["participants"], $_POST["date"]);
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
        else
        {
            $this->redirect("index.php?route=list_admin"); //faire un chemin pour delete
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
        
        $groupId = (int) $_GET['id'];

        $ctrlCategory = new CategoryManager;
        $categorys = $ctrlCategory->findAll();

        $ctrlGroupUser = new Group_userManager;
        $groupUsers = $ctrlGroupUser->findUsersByGroupId($groupId);

        $this->render("member/expenses.html.twig", [
            "categorys" => $categorys,
            "users" => $groupUsers
        ]);
    }

    public function balances() : void //page initiale lorsque l'on clique sur un groupe
    {
        $this->render("member/balances.html.twig", []);
    }


    public function home()
    {
        if (!isset($_SESSION['id'])) {
            $this->redirect('index.php?route=login');
        }

        $userId = $_SESSION['id'];

        $manager = new GroupManager();
        $groups = $manager->findAll();

        $manager2 = new Group_userManager();
        $myGroups = $manager2->findGroupsByUserId($userId);
        
        return $this->render('home/home.html.twig', ["groups" => $groups, "myGroups" => $myGroups]);
    }
    // Dans controllers/UserController.php

    // public function home()
    // {
    //     // 1. Vérifier si l'utilisateur est connecté
    //     if (!isset($_SESSION['id'])) {
    //         $this->redirect('index.php?route=login');
    //     }

    //     $userId = $_SESSION['id'];

    //     // 2. Utiliser le Group_userManager pour récupérer SES groupes
    //     $manager = new Group_userManager();
    //     $myGroups = $manager->findGroupsByUserId($userId); 

    //     // 3. Envoyer à la vue
    //     return $this->render('home/home.html.twig', ["groups" => $myGroups]);
    // }
}
