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

//<ex2-104>
if(!empty($_GET['REPORT_STATUS'])){
    if ($_GET['TEXT']){?>
        <script>
            let __reportStatus = document.getElementById('reports_status');
            __reportStatus.innerText = '<?=$_GET['TEXT']?>';
        </script>
    <?php    }
}elseif (!empty($_GET['REPORT_MODE']) && !empty($_GET['NEWS_ID'])){
    if ($_GET['REPORT_MODE'] == 'AJAX'){
        $APPLICATION->RestartBuffer();
        $repostResult = Ex2\Tasks\Report::setElementAdd($_GET['NEWS_ID']);
        echo CUtil::PhpToJSObject($repostResult);
        die();
    }else{
        $repostResult = Ex2\Tasks\Report::setElementAdd($_GET['NEWS_ID']);
        $currentPage = $APPLICATION->GetCurPageParam("REPORT_STATUS=".$repostResult['REPORT_STATUS']."&TEXT=".$repostResult['REPORT_MSG'], array("REPORT_MODE", "NEWS_ID", "REPORT_STATUS", "TEXT"));

        LocalRedirect($currentPage);
    }
}
//</ex2-104>