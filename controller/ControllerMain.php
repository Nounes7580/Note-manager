<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller {

    // Redirect to notes if logged in, otherwise show the home view.
    public function index() : void {
        if ($this->user_logged()) {
            $this->redirect("notes", "index");
        } else {
            (new View("login"))->show();
        }
    }

    
    // Handle user login
    public function login() : void {
        // Check if the user is already logged in
        if ($this->user_logged()) {
            // Redirect to the notes page
            $this->redirect("notes", "index");
        } else {
            // Proceed with normal login process
            $mail = '';
            $password = '';
            $errors = [];
            if (isset($_POST['mail']) && isset($_POST['password'])) {
                $mail = $_POST['mail'];
                $password = $_POST['password'];

                $errors = User::validate_login($mail, $password);
                if (empty($errors)) {
                    $this->log_user(User::get_user_by_mail($mail), "notes", "index");            
                }
            }
            (new View("login"))->show(["mail" => $mail, "password" => $password, "errors" => $errors]);
        }
    }

    // Handle user logout
    public function logout() : void {
        $this->logout_user();
        $this->redirect("main", "login");
    }

    public function logout_user() : void {
        unset($_SESSION['user']);
    }

    // Handle user signup
    public function signup() : void {
        // Check if the user is already logged in
        if ($this->user_logged()) {
            // Redirect to the notes page
            $this->redirect("notes", "index");
        } else {
            // Proceed with normal signup process
            $mail = ''; 
            $full_name = '';
            $password = '';
            $password_confirm = '';
            $errors = [];

            if (isset($_POST['mail']) && isset($_POST['full_name']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
                $mail = trim($_POST['mail']);
                $full_name = trim($_POST['full_name']);
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];


            // Updated User instantiation with full_name and a default role (if you have roles)
            $user = new User($mail, Tools::my_hash($password), $full_name, 'user'); 
            $errors = User::validate_email_unicity($mail);            // You may need to update the validate method in the User class or create a new one for mail and full name
            $errors = array_merge($errors, $user->validate()); 
            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));
    
            if (count($errors) == 0) { 
                $user->persist();
                $this->log_user($user, "notes", "index");
            }
        }

        (new View("signup"))->show(["mail" => $mail, "full_name" => $full_name, "password" => $password, 
                                    "password_confirm" => $password_confirm, "errors" => $errors]);
    }
}

    
}