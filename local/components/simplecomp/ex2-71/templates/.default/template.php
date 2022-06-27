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
<p>
    <?= GetMessage("EX2_107_TIME_STAMP") ?><? echo time(); ?>
</p>
___
<p>Каталог: </p>
<ul>
    <?foreach($arResult['GROUP_CUSTOM_SECTIONS'] as $arItem):?>
                 <li>
                     <?=$arItem['NAME']?>
                     <?if($arItem['ITEMS']):?>
                        <ul>
                        <? $findElem = [];
                        foreach($arItem['ITEMS'] as $elemId):?>
                            <?

                            $this->AddEditAction($arItem['ID']."_".$elemId, $arResult['ELEMENTS'][$elemId]['ADD_LINK'], CIBlock::GetArrayByID($arResult['ELEMENTS'][$elemId]["IBLOCK_ID"], "ELEMENT_ADD"));
                            $this->AddEditAction($arItem['ID']."_".$elemId, $arResult['ELEMENTS'][$elemId]['EDIT_LINK'], CIBlock::GetArrayByID($arResult['ELEMENTS'][$elemId]["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($arItem['ID']."_".$elemId, $arResult['ELEMENTS'][$elemId]['DELETE_LINK'], CIBlock::GetArrayByID($arResult['ELEMENTS'][$elemId]["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BPS_ELEMENT_DELETE_CONFIRM')));
                            ?>
                            <li  id="<?=$this->GetEditAreaId($arItem['ID']."_".$elemId);?>">
                                <?=$arResult['ELEMENTS'][$elemId]['NAME'];?> -
                                <?=$arResult['ELEMENTS'][$elemId]['PROPERTIES']['ARTNUMBER']['VALUE'];?> -
                                <?=$arResult['ELEMENTS'][$elemId]['PROPERTIES']['PRICE']['VALUE'];?> -
                                <?=$arResult['ELEMENTS'][$elemId]['PROPERTIES']['MATERIAL']['VALUE'];?> -
                                (<?=$arResult['ELEMENTS'][$elemId]['DETAIL_PAGE_URL'];?> )
                            </li>
                        <? endforeach; ?>
                        </ul>
                     <? endif;?>
                &nbsp;</li>
    <? endforeach; ?>
 </ul>
