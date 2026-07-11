<?php
namespace App\Core;

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        $viewFile = APP_PATH . "/Views/$view.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new \Exception("View not found: $view");
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}