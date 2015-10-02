<?php
namespace SoftUni;

class Autoloader
{
    public static function init()
    {
        spl_autoload_register(function($class) {
            $pathParams = explode("\\", $class);
            $path = implode(DIRECTORY_SEPARATOR, $pathParams);
            $path = str_replace($pathParams[0], "", $path);

            require_once $path . '.php';
        });
    }
}