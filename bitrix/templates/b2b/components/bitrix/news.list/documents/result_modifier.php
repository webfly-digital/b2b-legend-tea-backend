<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


foreach ($arResult["ITEMS"] as $arItem) {
    $listSect [] = $arItem["IBLOCK_SECTION_ID"];
}
$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y', 'ID' => $listSect);
$db_list = CIBlockSection::GetList(["SORT" => "ASC"], $arFilter, true, ['ID', 'NAME']);
while ($ar_result = $db_list->GetNext()) {
    if ($ar_result["ELEMENT_CNT"] > 0)
        $arResult["SECTIONS"][$ar_result['ID']] = $ar_result;
}