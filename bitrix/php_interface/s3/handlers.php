<?php
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnBeforeUserRegister', ['\Webfly\Handlers\Main', 'OnBeforeUserRegisterHandler']);
$eventManager->addEventHandler('main', 'OnAfterUserRegister', ['\Webfly\Handlers\Main', 'OnAfterUserRegisterHandler']);
$eventManager->addEventHandler('main', 'OnBeforeUserAdd', ['\Webfly\Handlers\Main', 'OnBeforeUserAddUpdateHandler']);
$eventManager->addEventHandler('main', 'OnBeforeUserUpdate', ['\Webfly\Handlers\Main', 'OnBeforeUserAddUpdateHandler']);
$eventManager->addEventHandler('main', 'OnAfterUserLogin', ['\Webfly\Handlers\Main', 'OnAfterUserLoginHandler']);
//$eventManager->addEventHandler('main', 'OnBeforeUserLogin', ['\Webfly\Handlers\Main', 'OnBeforeUserLoginHandler']);

$eventManager->addEventHandler('sale', 'OnSaleBasketBeforeSaved', ['\Webfly\Handlers\Sale', 'OnSaleBasketBeforeSavedHandler']);
$eventManager->addEventHandler('sale', 'OnSaleOrderBeforeSaved', ["\Webfly\Handlers\Sale", "OnSaleOrderBeforeSavedHandler"]);
$eventManager->addEventHandler('sale', 'OnSaleComponentOrderProperties', ["\Webfly\Handlers\Sale", "OnSaleComponentOrderPropertiesHandler"]);


$eventManager->addEventHandler('main', 'OnProlog', ["\Webfly\Handlers\Main", "OnPrologHandler"]);


