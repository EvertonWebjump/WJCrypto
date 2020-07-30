<?php

$app->middleware('before', function ($c) use ($router) {
    if (!preg_match('/^\/api\/*./', $router->getCurrentUrl())) {
        return;
    }

    $data = (new \App\controllers\AuthController)->getUser($c);

    $c['loggedUser'] = function () use ($data) {
        return $data;
    };
});
