<?php

require __DIR__.'/vendor/autoload.php';

$container = new \Pimple\Container();

$router = new \Framework\Router();

require  __DIR__. '/config/containers.php';
require __DIR__ . '/routes/web.php';

$app = new \Framework\App($container, $router);

require  __DIR__. '/config/middleware.php';

$app->run();
