<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"IBLOCK_ID_CATALOG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ID_CATALOG"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"IBLOCK_ID_NEWS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_ID_NEWS"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CODE_UF_PROP" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CODE_UF_PROP"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>180),
	),
);
?>