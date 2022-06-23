<?php
namespace Ex2\Handlers;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Localization\Loc;
Loc::LoadMessages(__FILE__);

class ProductDeactivation{

    public static function onBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == PRODUCT_IBLOCK_ID && $arFields['ACTIVE'] !== 'Y') {

            $resElement = \CIBlockElement::GetByID($arFields['ID']); $iShowCounter = 0;
            if ($arElement = $resElement->Fetch()) {
                $iShowCounter = $arElement['SHOW_COUNTER'];
            }

            if ($iShowCounter > MIN_SHOW_COUNTER) {
                global $APPLICATION;
                $APPLICATION->throwException(Loc::getMessage('SHOW_COUNTER_ERROR_1') . $iShowCounter . Loc::getMessage('SHOW_COUNTER_ERROR_2'));
                return false;
            }
        }
    }

}