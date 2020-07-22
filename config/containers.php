<?php

$container['settings'] = function (){
    return [
        'db' => [
            'dsn' => 'mysql:host=192.168.99.100;',
            'database' => 'wjcrytodb',
            'username' => 'teste',
            'password' => 'teste',
            'options' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        ]
    ];
};

$container['db'] = function ($c){
    $dsn = $c['settings']['db']['dsn'] . 'dbname=' . $c['settings']['db']['database'];
    $username = $c['settings']['db']['username'];
    $password = $c['settings']['db']['password'];
    $options = $c['settings']['db']['options'];

    $pdo = new \PDO($dsn, $username, $password, $options);

    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

$container['user_model'] = function ($c) {
    return new \App\models\User($c);
};
