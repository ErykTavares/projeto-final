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
    $calledController = new \controllers\CalledController();

    Router::get("/home", function() use($homeController ){
        $homeController->index();
    });

    Router::get("/chamado", function() use($calledController){
        if(isset($_GET['token'])){
            if($calledController->existingToken()){
                $calledController->index();
            }
            else{
                die("O token está setado, porém não existe");
            }
        }else{
            die("O token do chamado não foi encontrado. Interações com o chamado só são permitidas mediante a presença de um token válido.");
        }
        
    });
    

?>
