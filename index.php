<?php
    define("HOST", "localhost");
    define("DATABASE", "suporte_personalizado");
    define("USER", "root");
    define("PASSWORD", "");
    define("BASE", "http://localhost/projeto%20final/");

    require 'vendor/autoload.php';
    
    $autoload = function($class){
        include $class.'.php';
    };

    spl_autoload_register($autoload);

    $homeController = new \controllers\HomeController();

    Router::get("/home", function() use($homeController ){
        $homeController->index();
    });

    Router::get("/chamado", function(){
      echo '<h2>pagina do chamado</h2>';
    });
    

?>
