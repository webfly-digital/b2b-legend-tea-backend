<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent("bitrix:menu.sections", "", array(
    "IS_SEF" => "Y",
    "ID" => $_REQUEST["ID"],
    "IBLOCK_TYPE" => "1c_catalog",
    "IBLOCK_ID" => CATALOG_IBLOCK_ID,
    "SECTION_URL" => "",
    "DEPTH_LEVEL" => "2",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "360000",
    "SECTION_PAGE_URL" => "catalog/#SECTION_CODE_PATH#/",
),
    false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
