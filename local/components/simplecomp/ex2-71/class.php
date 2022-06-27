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

    // ex2-49
    // Фильтр должен применяться, если в адресной строке присутствует параметр «F», с любым значением.
    protected $bFilter = false;

    public function __construct($component = null)
    {
        if (isset($_REQUEST["F"])) {
            $this->bFilter = true;
        }

        parent::__construct($component);
    }
	public function onPrepareComponentParams($arParams)
	{
        $arParams["IBLOCK_ID_CATALOG"] = intval($arParams["IBLOCK_ID_CATALOG"]);
        $arParams["IBLOCK_ID_CUSTOM_SECTION"] = intval($arParams["IBLOCK_ID_CUSTOM_SECTION"]);
		$arParams['CODE_CUSTOM_PROP'] = trim($arParams['CODE_CUSTOM_PROP']);
		$arParams['DETAIL_URL'] = trim($arParams['DETAIL_URL']);

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
            "CODE",
        );
        //WHERE
        $arFilter = array(
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID_CATALOG"],
            "CHECK_PERMISSIONS" => $this->arParams["CACHE_GROUPS"],
            "PROPERTY_".$this->arParams['CODE_CUSTOM_PROP'] =>  $arClassIDs,
            "ACTIVE"=>"Y",
        );
        //
        if($this->bFilter){
            $arFilter[] = array(
                "LOGIC" => "OR",
               [
                   '<=PROPERTY_PRICE' => 1700,
                   'PROPERTY_MATERIAL' => 'Дерево, ткань',
               ],
               [
                   '<=PROPERTY_PRICE' => 1500,
                   'PROPERTY_MATERIAL' => 'Металл, пластик',
               ],
            );

        }
        // ex2-81
        // Установить сортировку отбираемых элементов из информационного блока каталога товаров:
        // сначала по наименованию, затем по полю сортировки.
        $arSort = array(
            "NAME"=> 'ASC',
            "SORT"=> 'ASC',
        );
        $resElements = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        // ex2-81
        if($this->arParams["DETAIL_URL"]){
            $resElements->SetUrlTemplates($this->arParams["DETAIL_URL"]);
        }

        while ($ob = $resElements->GetNextElement()) {
            $arEl = $ob->GetFields();

            $arButtons = CIBlock::GetPanelButtons(
                $arEl["IBLOCK_ID"],
                $arEl["ID"],
                0,
                array("SECTION_BUTTONS" => false, "SESSID" => false)
            );
            $arEl["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
            $arEl["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
            $arEl["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

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

        if($APPLICATION->GetShowIncludeAreas()) {
            // <ex2-100>
            // Метод возвращает массив, описывающий набор кнопок для управления элементами инфоблока
            $arButtons = CIBlock::GetPanelButtons( $this->arParams["IBLOCK_ID_CATALOG"]);
            // Добавляет стандартные кнопки к компоненту, которые отображаются в области компонента в режиме редактирования сайта
            $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
            // Добавляет новую кнопку к тем кнопкам компонента, которые отображаются в области компонента в режиме редактирования сайта
            $this->AddIncludeAreaIcon(array(
                                            'URL' => $arButtons["submenu"]["element_list"]["ACTION_URL"],
                                            'TITLE' => GetMessage('EX2_100_TITLE_BTN'),
                                        ));
        }

        if($this->StartResultCache(false, [($this->arParams["CACHE_GROUPS"] === "N" ? false: $USER->GetGroups()), $this->bFilter] )) {
            $this->setArResult();
            if($this->bFilter){
                $this->AbortResultCache();
            }
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