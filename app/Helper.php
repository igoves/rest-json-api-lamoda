<?php

namespace Core;

class Helper
{

    public static function isJson($string): string
    {
        json_decode($string, true);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public static function showContent($content)
    {
        header('Content-Type: application/json');
        echo $content;
        die();
    }

}