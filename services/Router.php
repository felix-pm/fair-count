<?php

class Router
{
    private AuthController $ac;
    private UserController $uc;
    public function __construct()
    {
        $this->ac = new AuthController();
        $this->uc = new UserController();
    }

    public function handleRequest() : void
    {
        if(!empty($_GET['route'])) {
            if($_GET['route'] === 'home') {
                $this->uc->home();
            }
            else if($_GET['route'] === 'login') {
                $this->ac->login();
            }
            else if($_GET['route'] === 'register') {
                $this->ac->register();
            }
            else if($_GET['route'] === 'logout') {
                $this->ac->logout();
            }
            else if($_GET['route'] === 'profile') {
                $this->uc->profile();
            }
            else if($_GET['route'] === 'create_group') {
                $this->uc->create_group();
            }
            else if($_GET['route'] === 'create_user') {
                $this->uc->create_user();
            }
            else if($_GET['route'] === 'update_user') {
                $this->uc->update_user();
            }
            else if($_GET['route'] === 'update_group') {
                $this->uc->update_group();
            }
            else if($_GET['route'] === 'delete_user') {
                $this->uc->delete_user();
            }
            else if($_GET['route'] === 'delete_group') {
                $this->uc->delete_group();
            }
            else if($_GET['route'] === 'list_admin') {
                $this->uc->list_admin();
            }
            else if($_GET['route'] === 'show_user') {
                $this->uc->show_user();
            }
            else if($_GET['route'] === 'expenses') {
                $this->uc->expenses();
            }
            else
            {
                $this->ac->notFound();
            }
        }
        else
        {
            $this->ac->page_connexion();
        }
    }
}
