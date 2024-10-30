<?php 
    namespace controllers;

    class CalledController{

        public function index($info) {
            \views\MainView::render("called", $info);
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

        public function getInfo ($token){
            $sql = \MySql::startConnection()->prepare(" SELECT * FROM called WHERE called_token = ?");

            $sql->execute(array($token));
            return $sql->fetch();
        }
    }

?>
