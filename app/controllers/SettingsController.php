<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Settings.php';

class SettingsController extends Controller
{
    public function actionIndex()
    {

        $pageTitle = 'Settings';
        $shopInfo = Settings::getInfo();
        $pageContent = ROOT . '/app/views/settings.php';
        include_once ROOT . '/app/views/template.php';
    }

    public function actionUpdateInfo()
    {
        $info = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        Settings::updateInfo($info);
        header("Location: http://{$_SERVER['HTTP_HOST']}/settings");
    }
}