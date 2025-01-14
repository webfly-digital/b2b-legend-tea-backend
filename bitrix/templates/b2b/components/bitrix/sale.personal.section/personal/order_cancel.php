<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if ($arParams['SHOW_ORDER_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}
elseif ($arParams['ORDER_DISALLOW_CANCEL'] === 'Y')
{
	LocalRedirect($arResult['PATH_TO_ORDERS']);
}

global $USER;
if ($arParams['USE_PRIVATE_PAGE_TO_AUTH'] === 'Y' && !$USER->IsAuthorized())
{
	LocalRedirect($arResult['PATH_TO_AUTH_PAGE']);
}

$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.cancel",
	"personal",
	array(
		"PATH_TO_LIST" => $arResult["PATH_TO_ORDERS"],
		"PATH_TO_DETAIL" => $arResult["PATH_TO_ORDER_DETAIL"],
		"AUTH_FORM_IN_TEMPLATE" => 'Y',
		"SET_TITLE" =>$arParams["SET_TITLE"],
		"ID" => $arResult["VARIABLES"]["ID"],
		"CONTEXT_SITE_ID" => $arParams["CONTEXT_SITE_ID"],
	),
	$component
);
?>
