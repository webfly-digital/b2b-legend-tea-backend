<?php

use \Bitrix\Main\EventManager;

$eventManager = \Bitrix\Main\EventManager::getInstance();


//main
//$eventManager->addEventHandler('main', 'OnBeforeUserAdd', ['\Webfly\Handlers\Main', 'OnBeforeUserAddHandler']);

//$eventManager->addEventHandler('main', 'OnAfterUserAdd', ['\Webfly\Handlers\Main', 'OnAfterUserAddHandler']);
//
//$eventManager->addEventHandler('main', 'OnAfterUserAuthorize', ['\Webfly\Handlers\Main', 'OnAfterUserAuthorizeHandler']);
//
//$eventManager->addEventHandler('main', 'OnBeforeUserRegister', ['\Webfly\Handlers\Main', 'OnBeforeUserRegisterHandler']);

//$eventManager->addEventHandler('main', 'OnAfterUserRegister', ['\Webfly\Handlers\Main', 'OnAfterUserRegisterHandler']);


//???$eventManager->addEventHandler('main', 'OnBeforeUserUpdate', ['\Webfly\Handlers\Main', 'OnBeforeUserAddUpdateHandler']);


//$eventManager->addEventHandler('main', 'OnAfterUserLogin', ['\Webfly\Handlers\Main', 'OnAfterUserLoginHandler']);
//$eventManager->addEventHandler('main', 'OnBeforeUserLogin', ['\Webfly\Handlers\Main', 'OnBeforeUserLoginHandler']);

//main
$eventManager->addEventHandler('main', 'OnProlog', ["\Webfly\Handlers\Main", "OnPrologHandler"]);
$eventManager->addEventHandler('main', 'OnPageStart', ["\Webfly\Handlers\Main", "OnPageStartHandler"]);


$eventManager->addEventHandler('sale', 'OnSaleBasketBeforeSaved', ['\Webfly\Handlers\Sale', 'OnSaleBasketBeforeSavedHandler']);
$eventManager->addEventHandler('sale', 'OnSaleOrderBeforeSaved', ["\Webfly\Handlers\Sale", "OnSaleOrderBeforeSavedHandler"]);
//$eventManager->addEventHandler('sale', 'OnSaleComponentOrderProperties', ["\Webfly\Handlers\Sale", "OnSaleComponentOrderPropertiesHandler"]);
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', ["\Webfly\Handlers\Sale", "OnSaleOrderSavedHandler"]);

