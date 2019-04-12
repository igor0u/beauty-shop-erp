<?php

require_once ROOT . '/app/models/Model.php';

class Settings
{
    public static function getInfo()
    {
        $filePath = ROOT . '/app/settings/shop-info.json';
        if (file_exists($filePath)) {
            $info = file_get_contents($filePath);
            return json_decode($info, true);
        }
        return false;
    }

    public static function updateInfo($info)
    {
        $filePath = ROOT . '/app/settings/shop-info.json';
        $infoJson = json_encode($info);
        file_put_contents($filePath, $infoJson);
    }
}