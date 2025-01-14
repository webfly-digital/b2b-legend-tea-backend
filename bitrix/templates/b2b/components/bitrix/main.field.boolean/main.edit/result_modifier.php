<?php

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UserField\Types\BooleanType;


$arResult['valueList'] = BooleanType::getLabels($arResult['userField']);
$arResult['value'] = (
(isset($arResult['userField']['VALUE']) && $arResult['userField']['VALUE'] !== false)
	? (int)$arResult['userField']['VALUE']
	: (int)$arResult['userField']['SETTINGS']['DEFAULT_VALUE']
);
