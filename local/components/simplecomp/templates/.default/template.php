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
?>
<? if(!empty($arResult['ITEMS'])){?>
    <b>
        <?= GetMessage("EX2_70_CATALOG") ?>
    </b>
    <ul>
    <? foreach ($arResult['ITEMS'] as $k => $arItem){ ?>
        <li>
            <b><?= $arItem["NAME"] ?></b> - <?= $arItem["DATE_ACTIVE_FROM"] ?>

            <? if (!empty($arItem["ITEMS"])): ?>
                <!-- Вывод секций в скобках -->
                (
                <? foreach ($arItem["ITEMS"] as $iKey => $arSection): ?>
                    <? $sComma = ""; ?>
                    <? if ($iKey != array_pop(array_keys($arItem["ITEMS"]))): ?>
                        <? $sComma = ","; ?>
                    <? endif; ?>
                    <?= $arSection["NAME"] . $sComma ?>
                <? endforeach; ?>
                )
                <!-- ./Вывод секций в скобках -->
                <!-- Вывод товаров по пунктам -->
                <ul>
                    <? foreach ($arItem["ITEMS"] as $iKey => $arSection): ?>
                        <? foreach ($arSection["ITEMS"] as $arElement): ?>
                            <?
                            // ex2-58
                            // ID, у элемента в DOM-дереве должен быть уникальным, но они дублируются в разных разделах,
                            // поэтому у некоторых редактирование не работает. Формируем уникальный,
                            // чтобы не пересекались.
                            $sElementId = $arItem["ID"] . $arSection["ID"] . $arElement["ID"];
                            $this->AddEditAction($sElementId, $arElement["EDIT_LINK"], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($sElementId, $arElement["EDIT_LINK"], CIBlock::GetArrayByID($arElement["IBLOCK_ID"], "ELEMENT_DELETE"),
                                array("CONFIRM" => GetMessage("EX2_58_ELEMENT_DELETE_CONFIRM")));
                            //End ex2-58
                            ?>
                                <li id="<?= $this->GetEditAreaId($sElementId); ?>">
                                    <?= $arElement["NAME"] ?> -
                                    <?= $arElement["PROPERTY_PRICE_VALUE"] ?> -
                                    <?= $arElement["PROPERTY_MATERIAL_VALUE"] ?> -
                                    <?= $arElement["PROPERTY_ARTNUMBER_VALUE"] ?>
                                </li>
                        <? endforeach; ?>
                    <? endforeach; ?>
                </ul>
                <!-- ./Вывод товаров по пунктам -->
            <? endif; ?>
        </li>
    <? } ?>
    </ul>
<? }?>