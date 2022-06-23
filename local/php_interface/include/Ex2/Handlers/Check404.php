<?php
namespace Ex2\Handlers;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Localization\Loc;
Loc::LoadMessages(__FILE__);


class Check404{

    public static function onEpilogHandler()
    {
        if (defined("ERROR_404") && ERROR_404 === "Y") {
            global $APPLICATION;

            \CEventLog::Add(
                [
                    "SEVERITY"      => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID"     => "main",
                    // Возвращает путь к текущей странице относительно корня вместе с параметрами
                    "DESCRIPTION"   => $APPLICATION->GetCurUri(),
                ]
            );
        }
    }

}