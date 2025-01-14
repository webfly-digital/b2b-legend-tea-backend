<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if ($arParams['SHOW_PRIVATE_PAGE'] !== 'Y' && $arParams['USE_PRIVATE_PAGE_TO_AUTH'] !== 'Y')
{
    LocalRedirect($arParams['SEF_FOLDER']);
}

if ($arParams['SET_TITLE'] == 'Y')
{
    $APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PRIVATE"));
}

if (!$USER->IsAuthorized())
{
    ob_start();
    $APPLICATION->AuthForm('', false, false, 'N', false);
    $authForm = ob_get_clean();
    echo $authForm;
}
else
{
    $APPLICATION->IncludeComponent(
        "webfly:change.password",
        "personal",
        Array(
        ),
        $component
    );
}

