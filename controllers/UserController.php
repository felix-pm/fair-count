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
                $this->render('admin/users/create.html.twig', []);
            }
            else
            {
                $this->render('admin/users/create.html.twig', ['errors' => $errors]);
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
            $this->render('admin/users/update.html.twig', ['datas' => $datas]);
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
            $this->redirect("index.php?route=list");
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
            $this->render('admin/users/index.html.twig', ['datas' => $datas]);
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

    //FONCTION POUR PAR DEFAUT
    public function profile() :void
    {
        if(isset($_SESSION["firstName"]) && isset($_SESSION["lastName"]) && isset($_SESSION["email"]) && isset($_SESSION["role"]) && isset($_SESSION["id"]))
        {
            if($_SESSION["role"] === "ADMIN")
            {
                $this->redirect('index.php?route=admin');
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

                                                            //modifié pour que ça marche 
    public function create_group() :void {
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
                $this->render('admin/users/create.html.twig', []);
            }
            else
            {
                $this->render('admin/users/create.html.twig', ['errors' => $errors]);
            }
            
        }
        
        }
    }

    public function update_group() : void {
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
            $this->render('admin/users/update.html.twig', ['datas' => $datas]);
        }
    }

    public function delete_group() : void
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN')
        {
            $this->redirect('index.php?route=login');
        }
        else
        {
            $this->redirect("index.php?route=list");
        }
    }

    public function expenses() : void //page des dépenses
    {
        $this->render("user/expenses.html.twig", []);
    }

    public function balances() : void //page initiale lorsque l'on clique sur un groupe
    {
        $this->render("user/balances.html.twig", []);
    }
}
