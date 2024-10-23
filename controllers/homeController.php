<?php 
    namespace controllers;

    class HomeController{
        public function index() {
            \views\MainView::render("home");
        }
    }

?>
