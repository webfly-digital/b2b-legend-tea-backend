<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$db_region = \CIBlockElement::getList(['NAME'=>'ASC'], ['IBLOCK_ID' => 106], false, false, ['ID', 'NAME']);
while ($ob_region = $db_region->fetch()) $arResult["REGION"][$ob_region['ID']] = $ob_region['NAME'];
