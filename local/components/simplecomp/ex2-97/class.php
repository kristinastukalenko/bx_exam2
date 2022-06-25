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

use Bitrix\Main\Loader;

class CAPConnectComponent extends \CBitrixComponent
{

	public function onPrepareComponentParams($arParams)
	{
        $arParams["NEWS_IBLOCK_ID"] = intval($arParams["NEWS_IBLOCK_ID"]);
		$arParams['CODE_NEWS_PROP'] = trim($arParams['CODE_NEWS_PROP']);
		$arParams['CODE_UF_USER_PROP'] = trim($arParams['CODE_UF_USER_PROP']);

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
		if(!Loader::includeModule('iblock')) {
			return false;
		}
		return true;
	}

	protected function setArResult()
	{
        global $USER;

        $rsCurrentUser = \CUser::GetById($USER->GetId());
        $arResult["CurrentUser"] = $rsCurrentUser->Fetch();

        // user
        // Получаем тип текущего пользователя по ID
        $iCurUserType = \CUser::GetList(
            ($by = "id"),
            ($order = "asc"),
            ["ID" => $USER->GetId()],
            ["SELECT" => [ $this->arParams["CODE_UF_USER_PROP"]]]
        )->Fetch()[$this->arParams["CODE_UF_USER_PROP"]];


        // users
        $arOrderUser = array("id");
        $sortOrder = "asc";
        $arFilterUser = array(
            "ACTIVE" => "Y",
            "!ID" => $USER->GetId(), //не текущий юзер
            // Отображаются только те авторы, у которых тот же «тип» что и у текущего пользователя
            $this->arParams["CODE_UF_USER_PROP"] => $iCurUserType,
        );

        $arResult["USERS"] = array();
        $rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser, [ "SELECT" => [ "LOGIN", "ID" ]]); // выбираем пользователей
        while($arUser = $rsUsers->GetNext()) {
            $arUserList[$arUser["ID"]] = ["LOGIN" => $arUser["LOGIN"]];
            $arUserIDs[] = $arUser["ID"];
        }

        if (!empty($arUserIDs)) {
            $arNewsList = [];

            //iblock elements
            $arSelectElems = array (
                "ID",
                "IBLOCK_ID",
                "NAME",
                "ACTIVE_FROM",
                "PROPERTY_".$this->arParams["CODE_NEWS_PROP"],
            );
            $arFilterElems = array (
                "IBLOCK_ID" => $this->arParams["NEWS_IBLOCK_ID"],
                "ACTIVE" => "Y",
                "!PROPERTY_".$this->arParams["CODE_NEWS_PROP"] => $USER->GetId(),
                "PROPERTY_".$this->arParams["CODE_NEWS_PROP"] => $arUserIDs,
            );
            $arSortElems = array (
                "NAME" => "ASC"
            );

            $rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
            while ($arNewsItem = $rsElements->Fetch()) {
                // Группируем новости по пользователям
                $iAuthorId = $arNewsItem["PROPERTY_" . $this->arParams["CODE_NEWS_PROP"] . "_VALUE"];
                $arUserList[$iAuthorId]["NEWS"][] = $arNewsItem;

                // Составляем массив из уникальных новостей для подсчета
                if (empty($arNewsList[$arNewsItem["ID"]])) {
                    $arNewsList[$arNewsItem["ID"]] = $arNewsItem;
                }
            };

            $this->arResult = [
                'COUNT' => count($arNewsList),
                'AUTHORS' => $arUserList,
                'USER_TYPE' => $iCurUserType,

            ];

        }
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

        // Неавторизованному пользователю данные не выводятся
        if ($USER->IsAuthorized()) {
            $iCurUserType = \CUser::GetList(
                ($by = "id"),
                ($order = "asc"),
                ["ID" => $USER->GetId()],
                ["SELECT" => [ $this->arParams["CODE_UF_USER_PROP"]]]
            )->Fetch()[$this->arParams["CODE_UF_USER_PROP"]];

            if($this->StartResultCache(false, ($this->arParams["CACHE_GROUPS"] === "N" ? false : $iCurUserType)) && !empty($iCurUserType)) {
                $this->setArResult();
                $this->SetResultCacheKeys(array(
                    "USER_TYPE",
                    "COUNT",
                ));
                $this->IncludeComponentTemplate();
            }

            // В компоненте устанавливать заголовок страницы: «Новостей: [Количество]».
            // Где Количество – количество элементов.
            // Заголовок должен устанавливаться в файле component.php.
            // Этот функционал является логикой компонента и не должен «теряться» при смене шаблона.
            $APPLICATION->SetTitle(GetMessage('TITLE_COMPONENT').$this->arResult['COUNT']);
        }
	}
}