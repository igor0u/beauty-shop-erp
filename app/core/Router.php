<?php


class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = require_once ROOT . '/app/routes.php';
    }

    public function run()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $this->handleRequest($uri);
    }

    public function handleRequest($uri)
    {
        if ($uri == '') {
            header("Location: /schedule");
        }
        foreach ($this->routes as $key => $value) {
            if (preg_match("~^{$key}$~", $uri)) {
                $segments = explode('/', $value);
                $controllerName = array_shift($segments) . 'Controller';
                $controllerPath = ROOT . '/app/controllers/' . $controllerName . '.php';
                if (file_exists($controllerPath)) {
                    require_once $controllerPath;
                    $controllerObject = new $controllerName;
                    $actionName = 'action' . array_shift($segments);
                    call_user_func(array($controllerObject, $actionName));
                }
                return;
            }
        }
    }
}