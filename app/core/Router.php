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
        $uri = strtolower(trim($_SERVER['REQUEST_URI'], '/'));
        $this->handleRequest($uri);
    }

    public function handleRequest($uri)
    {
        if ($uri == '') {
            header("Location: /schedule");
            return;
        }
        if (array_key_exists($uri, $this->routes)) {
            $segments = explode('/', $this->routes[$uri]);
        } else {
            echo '404';
            $segments = ['Error', 'Error404'];
        }
        $controllerName = array_shift($segments) . 'Controller';
        $controllerPath = ROOT . '/app/controllers/' . $controllerName . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerObject = new $controllerName;
            $actionName = 'action' . array_shift($segments);
            call_user_func(array($controllerObject, $actionName));
        } else {
            http_response_code(500);
            die('missing required files');
        }
    }
}