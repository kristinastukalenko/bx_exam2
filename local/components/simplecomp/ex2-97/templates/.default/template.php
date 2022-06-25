<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<? if($arResult['AUTHORS']){?>
    <ul>
    <?foreach($arResult["AUTHORS"] as $key => $arItem):?>
        <li>
            [<?=$key?>] - <?=$arItem['LOGIN']?>
            <? if($arItem['NEWS']){?>
                <ul>
                <?foreach($arItem['NEWS'] as $key2 => $arNews):?>
                    <li>
                        <?=$arNews['NAME']?> -
                        <?=$arNews['ACTIVE_FROM']?>
                    </li>
                <? endforeach;?>
                </ul>
             <? } ?>
        </li>
    <? endforeach;?>
    </ul>
<? } ?>