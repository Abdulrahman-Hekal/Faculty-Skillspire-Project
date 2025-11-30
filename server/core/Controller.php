<?php

// The parent controller class
class Controller
{
    public function requireView($view, $data = [])
    {
        if (file_exists(__DIR__ . "/../../views/{$view}.php")) {
            require_once __DIR__ . "/../../views/{$view}.php";
        } else {
            die("View does not exist: {$view}");
        }
    }

    public function requireModel($model)
    {
        require_once __DIR__ . "/../models/{$model}.php";
        return new $model();
    }
}
