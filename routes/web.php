<?php

$router->add("GET", "/", function () use($container){

    return 'API0 WJCrypto';
});

$router->add("POST", '/register', "\App\controllers\AuthController::register");
$router->add("POST", '/login', "\App\controllers\AuthController::login");
$router->add("GET", "/api/teste", function (){
    return 'API0 api';
});