<?php

$router->add("GET", "/", function () use($container){

    return 'API0 WJCrypto';
});

$router->add("POST", '/api/register', "\App\controllers\AuthController::register");
$router->add("POST", '/api/login', "\App\controllers\AuthController::login");