<?php
define("HOST", "localhost");
define("DATABASE", "suporte_personalizado");
define("USER", "root");
define("PASSWORD", "");
define("BASE", "http://localhost/projeto%20final/");

require 'vendor/autoload.php';

$autoload = function ($class) {
    include $class . '.php';
};

spl_autoload_register($autoload);

$homeController = new \controllers\HomeController();
$calledController = new \controllers\CalledController();
$adminController = new \controllers\AdminController();


Router::get("/home", function () use ($homeController) {
    $homeController->index();
});

Router::get("/chamado", function () use ($calledController) {
    $token = $_GET['token'];
    if (isset($token)) {
        if ($calledController->existingToken()) {
            $info = $calledController->getInfo($token);
            $calledController->index($info);
        } else {
            die("O token está setado, porém não existe");
        }
    } else {
        die("O token do chamado não foi encontrado. Interações com o chamado só são permitidas mediante a presença de um token válido.");
    }

});

Router::get("/admin", function () use ($adminController) {
    $adminController->index();
});


?>