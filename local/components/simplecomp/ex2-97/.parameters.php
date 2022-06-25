<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
            "PARENT" => "BASE",
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "CODE_NEWS_PROP"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODE_NEWS_PROP"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "CODE_UF_USER_PROP"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODE_UF_USER_PROP"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BPD_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
	),
);