<?php
    namespace views;

    class MainView {
        public static function render($file){
            include 'pages/'.$file.'.php';
        }

    }

?>
