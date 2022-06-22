<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	// ex2-34
	"SPECIAL_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_SPECIAL_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	// ex2-108
	"IBLOCK_ID_CANONICAL" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_IBLOCK_ID_CANONICAL"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
);
?>
