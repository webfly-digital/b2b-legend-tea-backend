<?php
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!check_bitrix_sessid()) {
    echo json_encode(['error' => 'Invalid session']);
    die();
}

if (!\Bitrix\Main\Loader::includeModule('sale')) {
    echo json_encode(['error' => 'Failed to load sale module']);
    die();
}

$fUserId = \Bitrix\Sale\Fuser::getId();
$basket = \Bitrix\Sale\Basket::loadItemsForFUser($fUserId, 's3'); // Костыль. Указываем правильный SITE_ID для b2b вручную, потому как он передаётся почему-то неверно.

$totalQuantity = 0;
foreach ($basket as $basketItem) {
    $totalQuantity += $basketItem->getQuantity();
}

echo json_encode([
    'NUM_PRODUCTS' => $totalQuantity
]);