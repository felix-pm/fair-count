<?php

class DefaultController extends AbstractController
{
    public function home() : void
    {
        $ctrl1 = new PlayerManager;
        $ctrl2 = new Player_performanceManager;
        $ctrl3= new GameManager;
        $data1 = $ctrl1->findAll();
        $data2 = $ctrl2->findAll();
        $data3 = $ctrl3->findAll();
        $data=[
            "players" => $data1,
            "performance" =>$data2,
            "game" =>$data3
        ];
        $this->render("home_page", $data);
    }

    public function list_matchs() : void
    {
        $ctrl = new GameManager;
        $data = $ctrl->findAll();
        $this->render("list_matchs", $data);
    }

    public function list_players() : void
    {
        $ctrl = new PlayerManager;
        $data = $ctrl->findPlayer();
        $this->render("list_players", $data);
    }

    public function list_teams() : void
    {
        $ctrl = new TeamManager;
        $data = $ctrl->findAll();
        $this->render("list_teams", $data);
    }

    public function matchs() : void
    {
        $ctrl1 = new Player_performanceManager;
        $ctrl2= new GameManager;
        $data1 = $ctrl1->findAll();
        $data2 = $ctrl2->findAll();
        $data=[
            "performance" =>$data1,
            "game" =>$data2
        ];
        $this->render("match", $data);
    }

    public function players() : void
    {
        $ctrl_1 = new PlayerManager;
        $ctrl_2 = new TeamManager;
        $ctrl_3 = new GameManager;
        $ctrl_4 = new Player_performanceManager;
        $data_1 = $ctrl_1->findAll();
        $data_2 = $ctrl_2->findAll();
        $data_3 = $ctrl_3->findAll();
        $data_4 = $ctrl_4->findAll();
        $data = [
            "players" => $data_1,
            "teams" => $data_2,
            "games" => $data_3,
            "stats" => $data_4
        ];
        $this->render("players", $data);
    }

    public function teams() : void
    {
        $ctrl = new PlayerManager;
        $data = $ctrl->findAll();
        $this->render("teams", $data);
    }
}