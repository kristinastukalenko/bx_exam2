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
$this->setFrameMode(true);
?>
___
<p>Каталог: </p>
<ul>
    <?foreach($arResult['GROUP_CUSTOM_SECTIONS'] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BPS_ELEMENT_DELETE_CONFIRM')));
            ?>
                 <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                     <?=$arItem['NAME']?>
                     <?if($arItem['ITEMS']):?>
                        <ul>
                        <?foreach($arItem['ITEMS'] as $elemId):?>
                            <li>
                                <?=$arResult['ELEMENTS'][$elemId]['NAME'];?> -
                                <?=$arResult['ELEMENTS'][$elemId]['PROPERTIES']['ARTNUMBER']['VALUE'];?> -
                                <?=$arResult['ELEMENTS'][$elemId]['PROPERTIES']['PRICE']['VALUE'];?> -
                                <?=$arResult['ELEMENTS'][$elemId]['PROPERTIES']['MATERIAL']['VALUE'];?> -
                                <a href="<?=$arResult['ELEMENTS'][$elemId]['DETAIL_PAGE_URL'];?>">Ссылка </a>
                            </li>
                        <? endforeach; ?>
                        </ul>
                     <? endif;?>
                &nbsp;</li>
    <? endforeach; ?>
 </ul>
