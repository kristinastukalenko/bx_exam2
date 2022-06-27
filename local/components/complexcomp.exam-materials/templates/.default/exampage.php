<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
// Значения переменных:
// ТЗ: На странице exampage.php вывести значения переменных PARAM1, PARAM2
echo 'SECTION_ID = ' . $arResult['VARIABLES']['SECTION_ID'] . '<br/>';
echo 'ELEMENT_ID = ' . $arResult['VARIABLES']['ELEMENT_ID'] . '<br/>';
echo 'PARAM1 = ' . $arResult['VARIABLES']['PARAM1'] . '<br/>';
echo 'PARAM2 = ' . $arResult['VARIABLES']['PARAM2'];
?>