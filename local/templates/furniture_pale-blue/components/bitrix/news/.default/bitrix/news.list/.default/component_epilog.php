<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */
/** @global CMain $APPLICATION */

global $APPLICATION;

if(!empty( $arResult['META_SPECIAL_DATE'])){
    $APPLICATION->SetPageProperty('specialdate', $arResult['META_SPECIAL_DATE']);
}