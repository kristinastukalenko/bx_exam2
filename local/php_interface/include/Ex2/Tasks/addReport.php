<?php
namespace Ex2\Tasks;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Localization\Loc;
Loc::LoadMessages(__FILE__);

class Report{

    public static function setElementAdd($newsId){

        global $USER;
        global $APPLICATION;

        if($USER->IsAuthorized()){
            $outputUser = "[".$USER->GetID()."] (".$USER->GetLogin().") ".$USER->GetFullName();
        }else{
            $outputUser = Loc::getMessage('USER_NOT_AUTH');
        }
        $newsId = intval($newsId);


        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => REPORT_IBLOCK_ID,
            "NAME"           => Loc::getMessage('EX2_104_REPORT_TITLE_IB').$newsId,
            "ACTIVE"         => "Y",            // активен
            "ACTIVE_FROM" => ConvertTimeStamp(time(),"FULL"),
            "PROPERTY_VALUES"=> array(
                'USER' => $outputUser,
                'NEWS' => $newsId
            ),
        );

        if($PRODUCT_ID = $el->Add($arLoadProductArray))
            return [ 'REPORT_MSG' => Loc::getMessage('EX2_104_REPORT_MSG_SUCCESS').$PRODUCT_ID, 'REPORT_STATUS' => 'Y'];
        else
           // echo "Ошибка! ".$el->LAST_ERROR;
            return ['REPORT_MSG' => Loc::getMessage('EX2_104_REPORT_MSG_FAIL'), 'REPORT_STATUS' => 'Y'];
    }
}