<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */
/** @global CMain $APPLICATION */

global $APPLICATION;

//<ex2-108>
/**
 * В component_epilog.php происходит проверка на наличие в массиве $arResult ключа CANONICAL
 * и если условие выполняется, мы устанавливаем заданное свойство страницы
 */

if (!empty($arResult["CANONICAL"]['NAME'])) {
    $APPLICATION->SetPageProperty("canonical", $arResult['CANONICAL']['NAME']);
}
//</ex2-108>