<?php

require_once ROOT . '/app/core/Router.php';

class App
{
    public $router;
    public $handler;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {

    }
}