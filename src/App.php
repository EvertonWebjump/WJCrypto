<?php


namespace Framework;


class App
{
    private $container;
    private $router;
    private $middlewares = [
        'before' => [],
        'after' => [],
    ];

    public function __construct($container, $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function run()
    {
        try{
            $result = $this->router->run();

            $response = new \Framework\Response();
            $params = [
                'container' => $this->container,
                'params' => $result['params']
            ];

            foreach ($this->middlewares['before'] as $middleware) {
                $middleware($this->container);
            }

            $response($result['action'], $params);

            foreach ($this->middlewares['after'] as $middleware) {
                $middleware($this->container);
            }

        } catch (\Framework\Exceptions\HttpException $exception){
            $this->container['exception'] = $exception;
            echo $this->getHttpErrorHandler();
        }
    }

    public function getHttpErrorHandler()
    {
        if (!$this->container->offsetExists('httpErrorHandler')){
            $this->container['httpErrorHandler'] = function ($c){
                header('Content-Type: application/json');

                $response = json_encode([
                    'code' => $c['exception']->getCode(),
                    'error' => $c['exception']->getMessage()]);

                return $response;
            };
        }

        return $this->container['httpErrorHandler'];
    }

    public function middleware($on, $callback)
    {
        $this->middlewares[$on][] = $callback;
    }

}