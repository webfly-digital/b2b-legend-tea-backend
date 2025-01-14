<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arResult['ORDERS']) {
    foreach ($arResult['ORDERS'] as $key => $order) {
        $arResult['ORDERS'][$key]['ORDER']["PRICE_FORMATED"] = \SaleFormatCurrency(
                $order['ORDER']["PRICE"],
                'RUB',
                true
            ) . ' ₽';

        if ($order['SHIPMENT']) {
            foreach ($order['SHIPMENT'] as $shKey => $shipment) {
                if (empty($shipment)) continue;
                $arResult['ORDERS'][$key]['SHIPMENT'][$shKey]['FORMATED_DELIVERY_PRICE'] = \SaleFormatCurrency(
                        $shipment['PRICE_DELIVERY'] ?: 0,
                        'RUB',
                        true
                    ) . ' ₽';
            }
        }
        if ($order['PAYMENT']) {

            if (is_array($order['PAYMENT'])) {
                if (count($order['PAYMENT']) > 1) {
                    $arResult['ORDERS'][$key]['PAYMENT'] = array_slice($arResult['ORDERS'][$key]['PAYMENT'], 1);
                }
                if (count($arResult['ORDERS'][$key]['PAYMENT']) > 1) {
                    unset ($arResult['ORDERS'][$key]['PAYMENT']);
                }
            }

            if ($arResult['ORDERS'][$key]['PAYMENT']) {
                foreach ($arResult['ORDERS'][$key]['PAYMENT'] as $pKey => $payment) {
                    $arResult['ORDERS'][$key]['PAYMENT'][$pKey]['FORMATED_SUM'] = \SaleFormatCurrency(
                            $payment['SUM'] ?: 0,
                            'RUB',
                            true
                        ) . ' ₽';
                }
            }
        }

        foreach ($order["BASKET_ITEMS"] as $basket) {
            $arElemIDs[$basket['PRODUCT_ID']] = $basket['PRODUCT_ID'];
            $arElemInfo[$key][$basket['PRODUCT_ID']] = $basket['PRODUCT_ID'];
        }
    }
}
if ($arElemIDs) {
    $resProduct = CCatalogSKU::getProductList($arElemIDs);
    if ($resProduct) foreach ($resProduct as $key => $prod) $arElemIDs[$key] = $prod['ID'];

    $db_res = CIBlockElement::GetList([], ["ID" => $arElemIDs], false, false, ['ID', 'IBLOCK_SECTION_ID']);
    while ($res = $db_res->GetNext()) {
        $arSect[$res['ID']] = $res['IBLOCK_SECTION_ID'];
    }

    if ($arSect) {
        foreach ($arSect as $key => $sect) {
            $nav = CIBlockSection::GetNavChain(false, $sect, ['ID', 'DEPTH_LEVEL']);
            while ($arSectionPath = $nav->GetNext()) {
                if ($arSectionPath['DEPTH_LEVEL'] == 1)
                    $listrSectionPath[$key] = $arSectionPath['ID'];

            }
        }

        foreach ($listrSectionPath as $key => $sect) {
            if (in_array($sect, SECTION_GENERATION_PDF)) {
                foreach ($arElemInfo as $order => $product) {
                    if (array_intersect(array_keys($arElemIDs, $key), $product)) {
                        $arResult['ORDERS'][$order]['ORDER']['GENERATE_PDF'] = true;
                        $arResult['ORDERS'][$order]['ORDER']['PRODUCT_IDS'] = array_intersect_key($arElemIDs, $product);

                        if ($arResult['ORDERS'][$order]['ORDER']["PERSON_TYPE_ID"] == B2B_UR_PERSON_TYPE_ID) {

                            $orderObj = \Bitrix\Sale\Order::load($arResult['ORDERS'][$order]['ORDER']['ID']);
                            $propertyCollection = $orderObj->getPropertyCollection();
                            if ($propertyCollection) {
                                $logoPropValue = $propertyCollection->getItemByOrderPropertyCode('LOGO');
                                if ($logoPropValue) {
                                    $matches = [];
                                    $logoValue = $logoPropValue->getViewHtml();
                                    preg_match_all('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $logoValue, $matches);
                                    if ($matches[1] && $matches[1][0]) $arResult['ORDERS'][$order]['ORDER']['LOGO'] = $matches[1][0];

                                }
                            }

                        }

                    }
                }
            }
        }
    }
}




