<?php

class App
{
    // Default controller and method
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1. Resolve Controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';

            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Require the controller file
        $requirePath = __DIR__ . '/../controllers/' . $this->controller . '.php';
        
        if (file_exists($requirePath)) {
            require_once $requirePath;
        } else {
            // TODO: Redirect to 404 page
            http_response_code(404);
            die("404 - Page Not Found (Controller missing: {$this->controller})");
        }

        // Instantiate the controller
        $this->controller = new $this->controller;

        // 2. Resolve Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Resolve Params
        // Re-index array so it starts at 0 for the arguments
        $this->params = $url ? array_values($url) : [];

        // 4. Dispatch
        if (!method_exists($this->controller, $this->method)) {
             // TODO: Redirect to 404 page
             die("Method '{$this->method}' not found in " . get_class($this->controller));
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            // rtrim removes trailing slash, filter_var sanitizes
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}