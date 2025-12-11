<?php

class Router
{
    public function handleRequest(array $get) : void
    {
        if(isset($get["route"]))
        {
            if($get["route"] === "list_matchs")
            {
                $ctrl = new DefaultController();
                $ctrl->list_matchs();
            }
            else if($get["route"] === "list_players")
            {
                $ctrl = new DefaultController();
                $ctrl->list_players();
            }
            else if($get["route"] === "list_teams")
            {
                $ctrl = new DefaultController();
                $ctrl->list_teams();
            }
            else if($get["route"] === "match")
            {
                $ctrl = new DefaultController();
                $ctrl->matchs();
            }
            else if($get["route"] === "players")
            {
                $ctrl = new DefaultController();
                $ctrl->players();
            }
            else if($get["route"] === "teams")
            {
                $ctrl = new DefaultController();
                $ctrl->teams();
            }
        }
        else
        {
            $ctrl = new DefaultController();
            $ctrl->home();

        }
    }

}

?>