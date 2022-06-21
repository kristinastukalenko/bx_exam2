<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
/** @var object $cp */

global $APPLICATION;

$cp = $this->__component;
$metaSpecDate = current($arResult['ITEMS'])["ACTIVE_FROM"];
if (is_object($cp) && !empty($metaSpecDate)){
    $cp->arResult['META_SPECIAL_DATE'] = $metaSpecDate;
    $cp->SetResultCacheKeys(array('META_SPECIAL_DATE'));
}