<?php

class AuthController extends AbstractController
{
    public function home() : void
    {
        $this->render('home/home.html.twig', []);
    }

    public function login() : void
    {
        if (!empty($_POST)) {
            if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmPassword'])){
                $error = "Vos données ne sont pas toutes remplies !";
            }
            $manager = new UserManager();
            $users = $manager->findAll(); 
            foreach ($users as $user) {
                if ($user->getEmail() ==! $_POST['email']) {
                    $error = "Votre email est incorrect !";
                    break; 
                }
            }
            $email = $_POST['email'];
            $password = $_POST['password'];
            $manager = new UserManager();
            $user = $manager->findByEmail($email);
            if ($user) {
                if (password_verify($password, $user->getPassword())) {
                    $_SESSION["firstName"] = $user->getFirstName();
                    $_SESSION["lastName"] = $user->getLastName();
                    $_SESSION["email"] = $user->getEmail();
                    $_SESSION["user_role"] = $user->getRole();
                    $_SESSION["id"] = $user->getId();
                    $this->redirect('index.php?route=profile');
                }
                else {
                    $error = "Identifiants incorrects";
                }
            } else {
                $error = "Identifiants incorrects"; 
            }
        }
        $this->render('auth/login.html.twig', []);
    }

    public function logout() : void
    {
        session_destroy();
        $this->redirect('index.php');
    }

    public function register(): void
{
    $error = null;
    if (!empty($_POST)) {
        if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmPassword'])) {
            $error = "Vos données ne sont pas toutes remplies !";
        }
        elseif ($_POST['password'] !== $_POST['confirmPassword']) {
            $error = "Vos mots de passe ne sont pas identiques !";
        }
        else {
            $manager = new UserManager();
            $users = $manager->findAll(); 
            foreach ($users as $user) {
                if ($user->getEmail() === $_POST['email']) {
                    $error = "Votre email est déjà utilisé !";
                    break; 
                }
            }
        }
        if ($error === null) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userToCreate = new User(
                $_POST['firstName'],
                $_POST['lastName'],
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
    $this->render('auth/register.html.twig', ['error' => $error]);
}

    public function notFound() : void
    {
        $this->render('error/notFound.html.twig', []);
    }
}