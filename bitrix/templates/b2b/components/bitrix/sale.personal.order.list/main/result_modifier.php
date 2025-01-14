<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if ($arResult['ORDERS']) {
    foreach ($arResult['ORDERS'] as $key => $order) {
        $arResult['ORDERS'][$key]['ORDER']["PRICE_FORMATED"] = \SaleFormatCurrency(
                $order['ORDER']["PRICE"]?:0,
                'RUB',
                true
            ) . ' ₽';

        if (is_array($order['PAYMENT'])){
            if (count($order['PAYMENT'])>1){
                $arResult['ORDERS'][$key]['PAYMENT'] = array_slice($arResult['ORDERS'][$key]['PAYMENT'], 1);
            }
        }

    }
}
