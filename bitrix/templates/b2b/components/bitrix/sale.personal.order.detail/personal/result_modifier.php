<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$arResult["PRICE_FORMATED"] = \SaleFormatCurrency(
        $arResult["PRICE"],
        'RUB',
        true
    ) . ' ₽';

if ($arResult['BASKET']) {
    $productIDs = [];
    foreach ($arResult['BASKET'] as &$basketItem) {
        $arElemIDs[$basketItem['PRODUCT_ID']] = $basketItem['PRODUCT_ID'];

        if ($basketItem['PARENT']['ID']) {
            $productIDs[] = $basketItem['PARENT']['ID'];
        } else {
            $productIDs[] = $basketItem['PRODUCT_ID'];
        }
        $basketItem["BASE_PRICE_FORMATED"] = \SaleFormatCurrency(
                $basketItem["PRICE"],
                'RUB',
                true
            ) . ' ₽';

        $basketItem["FORMATED_SUM"] = \SaleFormatCurrency(
                $basketItem["PRICE"] * $basketItem["QUANTITY"],
                'RUB',
                true
            ) . ' ₽';
    }


    if ($productIDs)
        $arResult['DETAIL_INFO'] = \Webfly\Helper\Helper::getDetailInfo($productIDs);
}

if (count($arResult['PAYMENT']) > 1) {
    $arResult['PAYMENT'] = array_slice($arResult['PAYMENT'], 1);
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
            $nav = CIBlockSection::GetNavChain(false, $sect, ['ID']);
            while ($arSectionPath = $nav->GetNext()) {
                if (in_array($arSectionPath['ID'],SECTION_GENERATION_PDF)) {
                    if (array_search($key, $arElemIDs)) {
                        $arResult['ORDER']['GENERATE_PDF'] = true;
                        $arResult['ORDER']['PRODUCT_IDS'] = $arElemIDs;
                    }
                }
            }
        }
    }
}


if ($arResult['ORDER']['GENERATE_PDF'] && $arResult["PERSON_TYPE_ID"] == B2B_UR_PERSON_TYPE_ID) {
    foreach ($arResult ["ORDER_PROPS"] as $ORDER_PROP) {
        if ($ORDER_PROP['CODE'] == 'LOGO') {
            preg_match_all('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $ORDER_PROP['VALUE'], $matches);
            if ($matches[1] &&  $matches[1][0]) $arResult['ORDER']['LOGO'] = $matches[1][0];
        }
        if ($ORDER_PROP['IS_PROFILE_NAME'] == 'Y') {
            $nameCompany = $ORDER_PROP['VALUE'];
        }
    }
}

if (!empty($arResult['ORDER']['LOGO']) && !empty($nameCompany)) {
    $arResult['ORDER']['NAME_COMPANY'] = $nameCompany;
}
