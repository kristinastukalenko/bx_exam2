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

/*В result_modifier.php расширяем $arResult добавляя в него новый ключ CANONICAL, значением которого будет являться поля привязанного элемента и с помощью функции CBitrixComponent::setResultCacheKeys передаем данный ключ в некешируемую область, файл component_epilog.php*/

if ($arParams['IBLOCK_ID_CANONICAL']) {
    $arFilter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID_CANONICAL'],
        'PROPERTY_CANONICAL' => $arResult['ID'], // ID новости
    ];
    $arSelect = [ 'ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_CANONICAL',  ];

    $r = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    if ($res = $r->Fetch()) {
        $arResult['CANONICAL'] = $res;
    }

    $cp = $this->__component;
    if (is_object($cp) && !empty($arResult['CANONICAL'])){
        $cp->arResult['CANONICAL'] = $arResult['CANONICAL'];
        $cp->SetResultCacheKeys(array('CANONICAL'));
    }
}