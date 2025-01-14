<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

if (!empty($arResult['SEARCH'])) {
    foreach ($arResult['SEARCH'] as $key => $item) {
        $arIds[$item["ITEM_ID"]] = $item["ITEM_ID"];
        $arIblockId = $item["PARAM2"];
    }
}


if ($arIds && $arIblockId) {
    $arOffersIds = [];
    $arOffersProductIds = [];
    $arOffersPrice = [];
    $resOffers = CCatalogSKU::getOffersList($arIds);
    if (!empty($resOffers)) {
        foreach ($resOffers as $key => $offer) {
            $arOffersProductIds[$key] = array_keys($offer);
            $arOffersIds = array_merge($arOffersIds, array_keys($offer));
        }
    }

    $arSelect = array("ID", "NAME", "IBLOCK_ID", "DETAIL_PICTURE", 'CATALOG_TYPE', "CATALOG_PRICE_24");
    $arFilter = ["ID" => array_merge($arOffersIds, $arIds), 'ACTIVE' => 'Y',];
    \Webfly\Helper\Functions::changeFilterOpt($arFilter);

    $res = CIBlockElement::GetList(['CATALOG_PRICE_24' => 'DESC'], $arFilter, false, false, $arSelect);

    while ($ob = $res->GetNext()) {
        if ($ob['CATALOG_TYPE'] == 4) {
            $arOffersPrice[$ob['ID']] = $ob['CATALOG_PRICE_24'];
        } else {
            $arResult['ITEMS'][$ob['ID']]['TITLE'] = $ob["NAME"];
            $arResult['ITEMS'][$ob['ID']]['DETAIL_PICTURE'] = $ob["DETAIL_PICTURE"] ? CFile::GetPath($ob["DETAIL_PICTURE"]) : '/bitrix/templates/b2b/assets/static/img/img-holder2.png';
            if (!empty($ob['CATALOG_PRICE_24'])) $arResult['ITEMS'][$ob['ID']]['PRICE'] = $ob['CATALOG_PRICE_24'];
        }
    }

    if (!empty($arOffersProductIds) && !empty($arOffersPrice)) {
        foreach ($arResult['ITEMS'] as $key => $item) {
            if (empty($item['PRICE'])) {
                foreach ($arOffersProductIds[$key] as $offer) {
                    $arResult['ITEMS'][$key]['PRICES'][] = $arOffersPrice[$offer];
                }
                if (!empty($arResult['ITEMS'][$key]['PRICES'])) $arResult['ITEMS'][$key]['PRICE'] = min($arResult['ITEMS'][$key]['PRICES']);
            }
        }
    }


    if (!empty($arResult["REQUEST"]['~QUERY'])) {
        $arSelect = array("ID", "NAME", "IBLOCK_ID", "CODE");
        $arFilter = ["%NAME" => $arResult["REQUEST"]['~QUERY'], 'ACTIVE' => 'Y', 'IBLOCK_ID' => $arIblockId];
        $res = CIBlockSection::GetList([], $arFilter, false, $arSelect, ['nTopCount' => 3]);
        while ($ob = $res->GetNext()) {
            $arResult['SECTIONS'][$ob['ID']]['NAME'] = $ob['NAME'];
            $arResult['SECTIONS'][$ob['ID']]['URL'] = '/catalog/' . $ob['CODE'] . '/';
        }

        \Webfly\Helper\Functions::countElementOpt($arResult['SECTIONS']);

    }
}