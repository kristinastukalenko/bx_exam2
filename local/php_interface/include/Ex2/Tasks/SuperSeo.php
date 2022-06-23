<?php
namespace Ex2\Tasks;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::LoadMessages(__FILE__);

/**
 * [ex2-94] Супер инструмент SEO специалиста
 *
 * Class SuperSeo
 */
class SuperSeo
{
    public static function run()
    {
        // Получаем текущую страницу
        global $APPLICATION;
        $sCurPage = $APPLICATION->GetCurPage();

        if (Loader::includeModule('iblock')) {
            // Получаем данные из ИБ
            $arFilter = array('IBLOCK_ID' => METATAGS_IBLOCK_ID, 'NAME' => $sCurPage);
            $arSelect = array('IBLOCK_ID', 'ID', 'PROPERTY_TITLE', 'PROPERTY_DESCRIPTION');
            $r = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

            // Если есть данные для текущей страницы, меняем
            if ($res = $r->Fetch()) {
                $APPLICATION->SetPageProperty('title', $res['PROPERTY_TITLE_VALUE']);
                $APPLICATION->SetPageProperty('description', $res['PROPERTY_DESCRIPTION_VALUE']);
            }
        }
    }
}