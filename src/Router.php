<?php


namespace Framework;

use Framework\Exceptions\HttpException;

class Router
{
    private $routes = [];

    public function add($method, $pattern, $callback)
    {
        $method = strtolower($method);
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        $this->routes[$method][$pattern] = $callback;
    }

    public function run()
    {
        $url = $this->getCurrentUrl();
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        if (empty($this->routes[$method])) {
            throw new HttpException("Page not found", 404);
        }

        foreach ($this->routes[$method] as $route => $action){
            if (preg_match($route, $url, $params)){
                return compact('action', 'params');
            }
        }

        throw new HttpException("Page not found", 404);
    }

    public function getCurrentUrl()
    {
        $url = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] :'/';

        if (strlen($url) > 1) {
            $url = rtrim($url, '/');
        }

        return $url;
    }

}