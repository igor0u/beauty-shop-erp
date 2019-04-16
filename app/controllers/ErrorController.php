<?php

require_once ROOT . '/app/controllers/Controller.php';

class ErrorController extends Controller
{
    public function actionError404()
    {
        http_response_code(404);
        $pageTitle = 'Page Not Found';
        $pageContent = ROOT . '/app/views/404.php';
        $modals = [];
        include_once ROOT . '/app/views/template.php';
    }

}