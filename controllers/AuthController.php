<?php

class AuthController extends AbstractController
{
    public function home() : void
    {
        $this->render('home/home.html.twig', []);
    }

    public function login() : void
    {        

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if( ((empty($_POST["email"]))) || (empty($_POST["password"])) )
            {  
                $errors[] = "veuillez remplir tout les champs !";
            }

            //condition de lancement de l'erreur pour le if ci-dessous
            $ctrl = new UserManager;
            $verif_email = $ctrl->findByEmail($_POST["email"]);

            //findAll qui permet d'aller récupérer le password dans le else ci-dessous
            $datas = $ctrl->findAll();

            //si l'utilisateur n'existe pas une erreur est lancé
            if($verif_email === null)
            {
                $errors = [];
                $errors[] = "L'utilisateur n'existe pas";
            }
            //si l'utilisateur existe il faut récupérer son password
            else
            {
                $errors = [];
                foreach($datas as $data)
                {
                    //si l'email passé dans le formulaire et l'email dans la base de données corresponde on récupére le password
                    if($data->getEmail() === $_POST["email"])
                    {
                        $hashedPassword = $data->getPassword();
                    }
                }                
            }

            if($verif_email != null)
            {
                //si le password entré dans le formulaire correspond a celui de la base de donnée il n'y a pas d'erreur
                if(password_verify($_POST["password"], $hashedPassword))
                {
                    $ctrl = new UserManager;
                    $datas = $ctrl->findAll();    
                    foreach($datas as $data)
                    {
                        if($data->getEmail() === $_POST["email"])
                        {
                            $_SESSION['id'] = $data->getId();   //affectation de l'id à la supervariable $_SESSION
                            $_SESSION['firstName'] = $data->getFirstName();
                            $_SESSION['lastName'] = $data->getLastName();
                            $_SESSION['email'] = $data->getEmail();
                            $_SESSION['password'] = $data->getPassword(); 
                            $_SESSION['role'] = $data->getRole();
                        }
                    }
                }
                else
                {
                    $errors[] = "Mot de passe incorrect";
                }
            }
            
            if(empty($errors) && !empty($_POST["email"]))
            {
                $this->redirect("index.php?route=profile");
            }

        }

        $this->render('auth/login.html.twig', ['errors' => $errors]);
    
    }


    public function logout() : void
    {
        session_destroy();
        $this->redirect('login.php');
    }

    public function register(): void
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            //vérification que tout les champs ont été rempli
            if( (empty($_POST["firstname"])) || (empty($_POST["lastname"])) || (empty($_POST["email"])) || (empty($_POST["password"])) || (empty($_POST["confirmPassword"])) )
            {  
                $errors[] = "veuillez remplir tout les champs !";
            }

            //vérification si l'utilisateur n'existe pas déjà
            $ctrl = new UserManager;
            $datas = $ctrl->findAll();
            $verif_email = true;
            foreach($datas as $data)
            {
                if($data->getEmail() === $_POST["email"])
                {
                    $verif_email = false;
                    break; // aide gemini 
                }
            }

            if($verif_email === false)
            {
                $errors[] = "utilisateur existe déjà !";
            }

            if($verif_email === true)
            {
                //vérification si password === confirmPassword
                if($_POST["password"] != $_POST["confirmPassword"])
                {
                    $errors[] = "veuillez remplir le même mot de passe !";
                }
            }
        if (empty($errors)) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userToCreate = new User(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['email'],
                $hashedPassword
            );
            $userToCreate->setRole("USER");
            $manager = new UserManager();
            $manager->create($userToCreate);
            $this->redirect('index.php?route=login');
            exit;
        }
    }
    $this->render('auth/register.html.twig', ['errors' => $errors]);
}

    public function notFound() : void
    {
        $this->render('error/notFound.html.twig', []);
    }
}