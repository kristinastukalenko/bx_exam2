<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init(array('ajax'));

?>
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div class="news-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?endif;?>
    <div class="reports">
        <? if($arParams["REPORT_AJAX"] == 'Y') {
            $currentPage = $APPLICATION->GetCurPageParam("REPORT_MODE=AJAX&NEWS_ID=".$arResult['ID'], array("REPORT_MODE", "NEWS_ID","TEXT","REPORT_STATUS"));
        ?>
            <a class="reports__link" href="<?=$currentPage?>" onclick="return false;"><?=GetMessage('EX2_100_REPORTS_TITLE_LINK')?></a>
            <script>
                BX.ready(function(){
                    let __reportStatus = document.getElementById('reports_status');
                    BX.bindDelegate(
                        document.body, 'click', {className: 'reports__link' },
                        function(e){
                            if(!e)
                                e = window.event;

                            BX.ajax.loadJSON(
                                '<?=$currentPage?>',
                                function (data) {
                                    console.log(data);
                                    __reportStatus.innerText = data.REPORT_MSG;
                                },
                                function () {
                                    __reportStatus.innerText = '<?=GetMessage('REPORT_ERROR')?>';
                                },
                            );
                            return BX.PreventDefault(e);
                        }
                    );

                });

            </script>
        <?} else{
            $currentPage = $APPLICATION->GetCurPageParam("REPORT_MODE=GET&NEWS_ID=".$arResult['ID'], array("REPORT_MODE", "NEWS_ID", "TEXT","REPORT_STATUS"));
        ?>
            <a href="<?=$currentPage?>"><?=GetMessage('EX2_100_REPORTS_TITLE_LINK')?></a>
        <? } ?>

       <div id="reports_status"></div>
       <br>---<br>
    </div>
	<div class="news-detail">
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
 	<?elseif($arResult["DETAIL_TEXT"] <> ''):?>
		<?echo $arResult["DETAIL_TEXT"];?>
 	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
	<?endforeach;?>
	<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;?>
	</div>
</div>