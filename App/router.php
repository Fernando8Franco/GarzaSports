<?php

class Router {
    private $controller;
    private $method;

    public function __construct() {
        $this->matchRoute();
    }

    public function matchRoute() {
        $url = explode('/', URL);
        $this->controller = !empty($url[1]) ? $url[1] : 'index';
        $this->method = !empty($url[2]) ? $url[2] : 'index';

        $this->controller = $this->controller.'Controller';
        $controllerFilePath = __DIR__.'/controllers/'.$this->controller.'.php';

        if(file_exists($controllerFilePath)) {
            require_once($controllerFilePath);
        } else {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }

    public function run() {
        $controller = new $this->controller();
        $method = $this->method;
        $controller->$method();
    }
}