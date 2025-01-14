<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale;

define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

if (!check_bitrix_sessid() || !$request->isPost())
    return;

$action = $request->get('action');

if (!$action) return;

Loader::includeModule('sale');
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Context::getCurrent()->getSite());
$sum_quantity = false;
switch ($action) {
    case 'add':
        $productId = $request->get('ID');
        $quantity = $request->get('QUANTITY');
        $sum_quantity = $request->get('SUM_QUANTITY');

        if ($item = $basket->getExistsItem('catalog', $productId)) {
            if ($sum_quantity == 'Y') {
                $existQuantity = $item->getQuantity();
                $quantity = $quantity + $existQuantity;
            }
            if ($quantity > 0)
                $res = $item->setField('QUANTITY', $quantity);
            else
                $res = $item->delete();
            if ($res->isSuccess()) {
                $basket->save();
            }
        } else {
            if ($quantity > 0) {
                $item = $basket->createItem('catalog', $productId);
                $res = $item->setFields(array(
                    'QUANTITY' => $quantity,
                    'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                    'LID' => Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => \Bitrix\Catalog\Product\Basket::getDefaultProviderName(),
                ));

                if ($res->isSuccess()) {
                    $basket->save();
                }

            }
        }
        break;
    case 'deleteAll':
        foreach ($basket as $item) {
            if ($item->canBuy() && !$item->isDelay()) {
                $res = $item->delete();
//                if ($res->isSuccess())
//                    $item->save();
            }
        }
        $basket->save();
        break;
    default:
        break;
}
echo json_encode(['success' => 'ok']);
