<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Application;
\Bitrix\Main\Loader::includeModule('sale');
/**
 * ЭДО
 */
$arResult['EDO_VALUES'] = [];
$dbVars = CSaleOrderPropsVariant::GetList(($by = "SORT"), ($order = "ASC"), Array("ORDER_PROPS_ID" =>B2B_EDO_ORDER_PROP));
while ($vars = $dbVars->GetNext())
    $arResult['EDO_VALUES'][] = $vars;
/**
 * Дополнительные данные из формы,
 * отправляемые юзером
 */
$context = Application::getInstance()->getContext();
$request = $context->getRequest();
if ($request->isPost()){
    $arResult['USER_SECOND_NAME'] = htmlspecialcharsbx($request->get('USER_SECOND_NAME'));
    $arResult['USER_PERSONAL_PHONE'] = htmlspecialcharsbx($request->get('USER_PERSONAL_PHONE'));
    $arResult['INDIVIDUAL'] = htmlspecialcharsbx($request->get('INDIVIDUAL'));
    $arResult['INN'] = htmlspecialcharsbx($request->get('INN'));
    $arResult['COMPANY'] = htmlspecialcharsbx($request->get('COMPANY'));
    $arResult['COMPANY_ADR'] = htmlspecialcharsbx($request->get('COMPANY_ADR'));
    $arResult['USE_EDO'] = htmlspecialcharsbx($request->get('USE_EDO'));
    $arResult['EDO'] = htmlspecialcharsbx($request->get('EDO'));
}

