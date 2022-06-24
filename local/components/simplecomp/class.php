<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
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

use Bitrix\Main\Loader;

class CAPConnectComponent extends \CBitrixComponent
{
	public function onPrepareComponentParams($arParams)
	{
        // Приводим значения к числу
        $arParams["IBLOCK_CATALOG_ID"] = (int)$arParams["IBLOCK_ID_CATALOG"];
        $arParams["IBLOCK_NEWS_ID"] = (int)$arParams["IBLOCK_ID_NEWS"];
        $arParams['CODE_UF_PROP'] = trim($arParams['CODE_UF_PROP']);

        // Значение по умолчанию, не обязательно, но желательно
        if (!$arParams["CACHE_TIME"]) {
            $arParams["CACHE_TIME"] = 3600;
        }

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

    /**
     * Производим выборку
     */
    public function setArResult()
    {
        // <Выборка разделов из ИБ "Продукция">
            $arFilter = [
                "IBLOCK_ID" => $this->arParams["IBLOCK_CATALOG_ID"],
                // Должны выбираться только активные разделы
                "ACTIVE" => "Y",
                // Фильтрация только разделов с новостями
                "!" . $this->arParams["CODE_UF_PROP"] => false,
            ];

            $arSelect = [
                "ID",
                "IBLOCK_ID",
                "NAME",
                $this->arParams["CODE_UF_PROP"],
            ];

            $res = CIBlockSection::GetList([], $arFilter, false, $arSelect);

            $arProductSectionsTotal = [];
            while ($arRes = $res->Fetch()) {
                $arProductSectionsTotal[] = $arRes;
            }

            // array_column — Возвращает массив из значений одного столбца входного массива
            $arProductSectionsId = array_column($arProductSectionsTotal, "ID");
        // </Выборка разделов из ИБ "Продукция">

        // <Выборка товаров из ИБ "Продукция" по выбранным разделам>
            $arFilter = [
                "IBLOCK_ID" => $this->arParams["IBLOCK_CATALOG_ID"],
                "ACTIVE" => "Y",
                // Выбирем элементы только по необходимым разделам
                'IBLOCK_SECTION_ID' => $arProductSectionsId,
            ];

            $arSelect = [
                "ID",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "NAME",
                "PROPERTY_PRICE",
                "PROPERTY_ARTNUMBER",
                "PROPERTY_MATERIAL",
            ];

            $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

            $iCount = 0;
            $arAllPrice = [];
            while ($arRes = $res->Fetch()) {
                $arAllPrice[] = $arRes["PROPERTY_PRICE_VALUE"];
                foreach ($arProductSectionsTotal as &$arSection) {
                    if ($arRes["IBLOCK_SECTION_ID"] == $arSection["ID"]) {
                        // <ex2-58>
                        $arButtons = CIBlock::GetPanelButtons(
                            $arRes["IBLOCK_ID"],
                            $arRes["ID"],
                            0,
                            ["SECTION_BUTTONS" => false, "SESSID" => false]
                        );
                        $arRes["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
                        $arRes["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
                        // </ex2-58>

                        // Добавляем элементы товаров к секциям
                        $arSection["ITEMS"][] = $arRes;
                    }
                }
                $iCount++;
            }
        // </Выборка товаров из ИБ "Продукция" по выбранным разделам>

        // <ex2-82>
            // Минимальная цена
            $this->arResult["MIN_PRICE"] = min($arAllPrice);
            // Максимальная цена
            $this->arResult["MAX_PRICE"] = max($arAllPrice);
        // </ex2-82>

        // Количество товаров
        $this->arResult["COUNT"] = $iCount;


        // <Выборка элементов из ИБ "Новости">
            $arFilter = [
                "IBLOCK_ID" => $this->arParams["IBLOCK_NEWS_ID"],
                "ACTIVE" => "Y",
            ];

            $arSelect = [
                "ID",
                "IBLOCK_ID",
                "NAME",
                "DATE_ACTIVE_FROM",
            ];

            $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            $i = 0;
            while ($arRes = $res->Fetch()) {
                $this->arResult["ITEMS"][$i] = $arRes;
                foreach ($arProductSectionsTotal as &$arSection2) {
                    foreach ($arSection2[$this->arParams["CODE_UF_PROP"]] as $item) {
                        if ($item == $arRes["ID"]) {
                            $this->arResult["ITEMS"][$i]["ITEMS"][] = $arSection2;
                        }
                    }
                }
                $i++;
            }
        // </Выборка элементов из ИБ "Новости">
    }

	public function executeComponent()
	{
        global $APPLICATION;
        global $USER;

		if(!$this->checkModules()) {
            ShowError(GetMessage("EX2_70_IB_CHECK"));
			return;
		}

        if ($this->StartResultCache()) {
            $this->setArResult();
            // Список ключей массива $arResult, которые должны кэшироваться при использовании встроенного кэширования компонентов, иначе закеширует весь массив arResult, кэш сильно разростается
            $this->setResultCacheKeys(
                [
                    "COUNT",
                    "MIN_PRICE",
                    "MAX_PRICE",
                ]
            );
            $this->includeComponentTemplate();

            $APPLICATION->SetTitle(GetMessage("EX2_70_ELEMENTS_COUNT") . $this->arResult["COUNT"]);


            // ex2-82
            // AddViewContent - позволяет указать место вывода контента, создаваемого ниже по коду с помощью метода ShowViewContent.
            $APPLICATION->AddViewContent(
                "min_price",
                GetMessage("EX2_82_MIN_PRICE") . $this->arResult["MIN_PRICE"]
            );
            $APPLICATION->AddViewContent(
                "max_price",
                GetMessage("EX2_82_MAX_PRICE") . $this->arResult["MAX_PRICE"]
            );
            // ./ex2-82
        }
	}
}