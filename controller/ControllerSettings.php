<?php
require_once "framework/Controller.php";
require_once "model/User.php";

class ControllerSettings extends Controller {
    public function index() : void {
        echo "<h1>Hello !</h1>";
    }

    public function settings() : void{
        $this->log_user(User::get_user_by_mail("boverhaegen@epfc.eu"));
        $user = $this->get_user_or_redirect();
        if($this->user_logged()){
            $this->redirect("settings","index");
        }
        else{
            (new View ("settings"))->show(array("user"=>$user));
        }

    }
}