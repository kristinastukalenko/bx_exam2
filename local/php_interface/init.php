<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Loader;

\CModule::AddAutoloadClasses(
    '',
    array(
       // 'Ex2\Debug' => "lib/debug.php",
        'Ex2\Handlers\ProductDeactivation' => "/local/php_interface/include/Ex2/Handlers/ProductDeactivation.php",
    )
);

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/constants.php"))
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/constants.php");

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/handlers.php"))
    require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/handlers.php");