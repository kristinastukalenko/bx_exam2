<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"IBLOCK_ID_CATALOG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ID_CATALOG"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"IBLOCK_ID_CUSTOM_SECTION" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ID_CUSTOM_SECTION"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CODE_CUSTOM_PROP" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CODE_CUSTOM_PROP"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
        // ex2-81
        "DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
            "DETAIL",
            "DETAIL_URL",
            GetMessage("IBLOCK_DETAIL_URL"),
            "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
            "URL_TEMPLATES"
        ),
        // ./ex2-60
        "ELEMENTS_COUNT"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("EX2_60_DESC_LIST_CONT"),
            "TYPE" => "STRING",
            "DEFAULT" => "2",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>180),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BPR_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
	),
);

CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    GetMessage("EX2_60_DESC_PAGER_NEWS"), //$pager_title
    true, //$bDescNumbering
    true, //$bShowAllParam
    false, //$bBaseLink
    false //$bBaseLinkEnabled
);