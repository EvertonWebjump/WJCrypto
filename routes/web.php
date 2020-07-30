<?php

$router->add("GET", "/", function () use($container){

    return 'API0 WJCrypto';
});

// authentication routes
$router->add("POST", '/register', "\App\controllers\AuthController::register");
$router->add("POST", '/login', "\App\controllers\AuthController::login");

//routes of operation to users
$router->add("GET", '/api/users', "\App\controllers\UserController::index");
$router->add("GET", '/api/user/(\d+)', "\App\controllers\UserController::show");
$router->add("POST", '/api/user', "\App\controllers\UserController::create");
$router->add("PUT", '/api/user/(\d+)', "\App\controllers\UserController::update");
$router->add("DELETE", '/api/user/(\d+)', "\App\controllers\UserController::delete");

//routes of operation to accounts
$router->add("GET", '/api/accounts', "\App\controllers\AccountController::index");
$router->add("GET", '/api/account/(\d+)', "\App\controllers\AccountController::show");
$router->add("POST", '/api/account', "\App\controllers\AccountController::create");
$router->add("PUT", '/api/account/(\d+)', "\App\controllers\AccountController::update");
$router->add("DELETE", '/api/account/(\d+)', "\App\controllers\AccountController::delete");

$router->add("PUT", '/api/account/transfer/sender/(\d+)/recipent/(\d+)', "\App\controllers\AccountController::transfer");
$router->add("PUT", '/api/account/deposit/(\d+)', "\App\controllers\AccountController::deposit");
$router->add("PUT", '/api/account/withdrawal/(\d+)', "\App\controllers\AccountController::withdrawal");

//routes of operation to addresses
$router->add("GET", '/api/addresses', "\App\controllers\AddressController::index");
$router->add("GET", '/api/address/(\d+)', "\App\controllers\AddressController::show");
$router->add("POST", '/api/address', "\App\controllers\AddressController::create");
$router->add("PUT", '/api/address/(\d+)', "\App\controllers\AddressController::update");
$router->add("DELETE", '/api/address/(\d+)', "\App\controllers\AddressController::delete");


