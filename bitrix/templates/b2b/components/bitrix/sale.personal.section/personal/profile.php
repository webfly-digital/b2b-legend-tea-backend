<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if ($arParams['SHOW_PROFILE_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}

global $USER;
if ($arParams['USE_PRIVATE_PAGE_TO_AUTH'] === 'Y' && !$USER->IsAuthorized())
{
	LocalRedirect($arResult['PATH_TO_AUTH_PAGE']);
}

if ($arParams["MAIN_CHAIN_NAME"] <> '')
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$APPLICATION->IncludeComponent(
	"webfly:sale.personal.profile.list",
	"personal",
	array(
		"PATH_TO_DETAIL" => $arResult['PATH_TO_PROFILE_DETAIL'],
		"PATH_TO_DELETE" => $arResult['PATH_TO_PROFILE_DELETE'],
		"PER_PAGE" => $arParams["PROFILES_PER_PAGE"],
		"SET_TITLE" =>'N',
		"AUTH_FORM_IN_TEMPLATE" => 'Y',
		'PERSON_TYPE_ID'=>[B2B_UR_PERSON_TYPE_ID,B2B_FIZ_PERSON_TYPE_ID]
	),
	$component
);
?>
