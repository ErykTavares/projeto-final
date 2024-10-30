<?php
    namespace views;

    class MainView {
        public static function render($file, $info = null){
            include 'pages/'.$file.'.php';
        }

    }

?>
