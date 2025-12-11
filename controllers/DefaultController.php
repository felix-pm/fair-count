<?php

class DefaultController extends AbstractController
{
    public function home() : void
    {

        $this->render("home/home.html.twig", []); //changer la route vers la page home
    }

    public function login() : void //page de connection
    {
        $this->render("auth/login.html.twig", []);
        
    }

    public function register() : void //page de création de compte
    {
        $this->render("auth/register.html.twig", []);
    }

    public function profile() : void //page de profil de l'utilisateur
    {
        $this->render("member/profile.html.twig", []);
    }

    public function expenses() : void //page des dépenses
    {
        $this->render("user/expenses.html.twig", []);
    }

    public function create_group() : void //création de groupe
    {
        $this->render("user/profile.html.twig", []);
    }

    public function update_group() : void //update d'un groupe par un utilisateur
    {
        $this->render("user/update_group.html.twig", []);
    }

    public function balances() : void //page initiale lorsque l'on clique sur un groupe
    {
        $this->render("user/balances.html.twig", []);
    }
}