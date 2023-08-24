<?php

class Controller
{
    protected function __construct(PDO $con)
    {
        session_start();
    }

    protected function render($path, $layout = '')
    {
        ob_start();
        require_once(__DIR__ . '/../views/' . $path . '.view.php');
        $content = ob_get_clean();

        require_once(__DIR__ . '/../views/layouts/' . $layout . '.layout.php');
    }

    protected function renderView($path) {
        require_once(__DIR__ . '/../views/' . $path . '.view.php');
    }
}