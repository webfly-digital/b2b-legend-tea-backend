<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Application;
$context = Application::getInstance()->getContext();
$request = $context->getRequest();
if ($request->isPost()){
    $arResult['USER_VALUE'] = $arResult['value'][0];
}
