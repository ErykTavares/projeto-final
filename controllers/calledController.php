<?php 
    namespace controllers;

    class CalledController{

        public function index() {
            \views\MainView::render("called");
        }

        public function existingToken() {
            $token = $_GET['token'];
            $check  = \MySql::startConnection()->prepare("SELECT * FROM called WHERE called_token = ?");
            
            $check->execute(array($token));

            if($check->rowCount() == 1){
                return true;
            }
            else{
                return false;
            }
        }
    }

?>
