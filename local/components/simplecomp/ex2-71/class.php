<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 */

use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

class CAPConnectComponent extends \CBitrixComponent
{

	public function onPrepareComponentParams($arParams)
	{
        $arParams["IBLOCK_ID_CATALOG"] = intval($arParams["IBLOCK_ID_CATALOG"]);
        $arParams["IBLOCK_ID_CUSTOM_SECTION"] = intval($arParams["IBLOCK_ID_CUSTOM_SECTION"]);
		$arParams['CODE_CUSTOM_PROP'] = trim($arParams['CODE_CUSTOM_PROP']);
		$arParams['TEMPLATE_DETAIL_URL'] = trim($arParams['TEMPLATE_DETAIL_URL']);

        if(!isset($arParams["CACHE_TIME"]))
            $arParams["CACHE_TIME"] = 180;


		return parent::onPrepareComponentParams($arParams);
	}

	/**
	 * Check Required Modules
	 *
	 * @throws Exception
	 */
	protected function checkModules()
	{
		if(!Loader::includeModule('iblock'))
		{
			return false;
		}

		return true;
	}

	protected function setArResult()
	{
        // <получить всех Классификаторов, так как по условию задачи их мало. Нет смысла получить 1000 товаров>
        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "NAME",
        );
        //WHERE
        $arFilter = array(
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID_CUSTOM_SECTION"],
            "ACTIVE"=>"Y",
            "CHECK_PERMISSIONS" => $this->arParams["CACHE_GROUPS"],
        );

        //EXECUTE
        $arResult["CUSTOM_SECTIONS"] = [];
        $resElements = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
        while ($arElement = $resElements->GetNext()) {
            $arResult["CUSTOM_SECTIONS"][] = $arElement;
        }


        $arClassIDs = array_column($arResult["CUSTOM_SECTIONS"], "ID");
        // </получить всех Классификаторов, так как по условию задачи их мало. Нет смысла получить 1000 товаров>
        
        // <получить все Продукты, с фильтром по классификатору>
        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "NAME",
            "DETAIL_PAGE_URL",
        );
        //WHERE
        $arFilter = array(
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID_CATALOG"],
            "CHECK_PERMISSIONS" => $this->arParams["CACHE_GROUPS"],
            "PROPERTY_".$this->arParams['CODE_CUSTOM_PROP'] =>  $arClassIDs,
            "ACTIVE"=>"Y",
        );
        $resElements = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
        while ($ob = $resElements->GetNextElement()) {
            $arEl = $ob->GetFields();
            // Т.к. св-во "FIRMS" множественное, а в ИБ хранятся св-ва в одной таблице (не в отдельной),
            // чтобы не было дублей - получаем св-ва через отдельный метод
            $arEl["PROPERTIES"] = $ob->GetProperties();
            $arResult["ELEMENTS"][$arEl["ID"]] = $arEl;
        }

        // </получить все Продукты, с фильтром по классификатору>
        $arResult["GROUP_CUSTOM_SECTIONS"] = [];
        foreach ($arResult["CUSTOM_SECTIONS"] as $key => $item) {
            $arResult["GROUP_CUSTOM_SECTIONS"][$item['ID']] = $item;
            foreach ($arResult["ELEMENTS"] as $index => $ELEMENT) {
                foreach ($ELEMENT["PROPERTIES"][$this->arParams['CODE_CUSTOM_PROP']]["VALUE"] as $iVal) {
                    if ($iVal == $item['ID']) {
                        $arResult["GROUP_CUSTOM_SECTIONS"][$item['ID']]['ITEMS'][] = $ELEMENT['ID'];
                        break;
                    }
                }
            }
        }

        $this->arResult =  [ 'CUSTOM_SECTIONS' => $arResult["CUSTOM_SECTIONS"],
                            'COUNT_SECTIONS' => count($arResult["CUSTOM_SECTIONS"]),
                            'ELEMENTS' => $arResult["ELEMENTS"],
                            'GROUP_CUSTOM_SECTIONS' => $arResult["GROUP_CUSTOM_SECTIONS"],
        ];
    }

	public function executeComponent()
	{
		global $APPLICATION;
		global $USER;

		if(!$this->checkModules())
		{
            ShowError(GetMessage("MODULE_NOT_INSTALLED"));
            return;
		}

        if($this->StartResultCache(false, ($this->arParams["CACHE_GROUPS"] === "N" ? false: $USER->GetGroups()))) {
            $this->setArResult();

            $this->SetResultCacheKeys(array(
                "COUNT_SECTIONS",
            ));
            $this->IncludeComponentTemplate();
        }

        // В компоненте устанавливать заголовок страницы: «Разделов: [Количество]».
        // Где Количество – количество элементов классификатора.
        //
        // Заголовок должен устанавливаться в файле component.php.
        // Этот функционал является логикой компонента и не должен «теряться» при смене шаблона.
        $APPLICATION->SetTitle(GetMessage('TITLE_COMPONENT').$this->arResult['COUNT_SECTIONS']);
	}
}