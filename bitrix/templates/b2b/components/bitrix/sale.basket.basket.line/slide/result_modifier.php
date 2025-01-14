<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!function_exists('setItemsSections')) {
    function setItemsSections($productIDs, &$sections, &$ready)
    {
        if (!$productIDs) return;
        $skuIDs = [];
        $res = CIBlockElement::getList([], ['ID' => $productIDs], false, false, ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PROPERTY_BREND_PROIZVODITELYA']);

        while ($ob = $res->fetch()) {
            if ($ob['PROPERTY_BREND_PROIZVODITELYA_ENUM_ID'] == '3812')
                $dataProduct[$ob['ID']] = $ob['PROPERTY_BREND_PROIZVODITELYA_VALUE'];
            if ($ob['IBLOCK_ID'] == CATALOG_IBLOCK_ID) {
                if (!in_array($ob['IBLOCK_SECTION_ID'], $sections))
                    $sections[] = $ob['IBLOCK_SECTION_ID'];

                array_walk($ready, function (&$n, $key, $ob) {
                    if ($n['PRODUCT_ID'] == $ob['ID'] || $n['PARENT_ID'] == $ob['ID']) {
                        $n['PRODUCT_SECTION_ID'] = $ob['IBLOCK_SECTION_ID'];
                    }
                }, $ob);
            } else {
                $skuIDs[] = $ob['ID'];
            }
        }

        foreach ($ready as $key => $item) {
            if ($dataProduct[$item['PRODUCT_ID']]) $ready[$key]['BREND'] = $dataProduct[$item['PRODUCT_ID']];
            if ($dataProduct[$item['PARENT_ID']]) $ready[$key]['BREND'] = $dataProduct[$item['PARENT_ID']];
        }

        if ($skuIDs) {
            $productIDs = [];
            foreach ($skuIDs as $sID) {
                $parentItem = CCatalogSku::GetProductInfo($sID, SKU_IBLOCK_ID);
                if ($parentItem["ID"]) {
                    $productIDs[] = $parentItem["ID"];
                    $map = ['SKU_ID' => $sID, 'PARENT_ID' => $parentItem["ID"]];

                    array_walk($ready, function (&$n, $key, $map) {
                        if ($n['PRODUCT_ID'] == $map['SKU_ID']) {
                            $n['PARENT_ID'] = $map['PARENT_ID'];
                        }
                    }, $map);

                }
            }
            if ($productIDs) {
                setItemsSections($productIDs, $sections, $ready);
            }
        }
    }
}

if ($arResult["CATEGORIES"]['READY']) {

    /**
     * Группировка товаров по корневым разделам
     */
    \Bitrix\Main\Loader::includeModule('iblock');
    \Bitrix\Main\Loader::includeModule('catalog');
    $ready = $arResult["CATEGORIES"]['READY'];
    if ($ready)
        $productIDs = array_column($ready, 'PRODUCT_ID');

    $sections = [];
    $arProductNewPrice = [];
    setItemsSections($productIDs, $sections, $ready);

    if ($sections) {
        $firstLvlSections = [];
        foreach ($sections as $sectionID) {
            $sParents = CIBlockSection::GetNavChain(CATALOG_IBLOCK_ID, $sectionID, ['ID', 'NAME', 'DEPTH_LEVEL'], true);
            $parentSection = current(array_filter($sParents, function ($v) {
                return $v['DEPTH_LEVEL'] == 1;
            }));
            if ($parentSection) {
                $firstLvlSections[$parentSection['ID']]['SUM'] = 0;
                $firstLvlSections[$parentSection['ID']]['ID'] = $parentSection['ID'];
                $firstLvlSections[$parentSection['ID']]['NAME'] = $parentSection['NAME'];
                $firstLvlSections[$parentSection['ID']]['PRODUCT_SECTION_ID'][] = $sectionID;
            }
        }
    }


    //CIBlockSection::GetNavChain не возвращает пользовательские поля, поэтому отдельный запрос для сортировки
    if ($firstLvlSections) {

        $sectionSort = CIBlockSection::getList([], ['IBLOCK_ID' => CATALOG_IBLOCK_ID, 'ID' => array_keys($firstLvlSections)], false, ['ID', 'UF_B2B_SORT'], false);
        while ($ob_sort = $sectionSort->fetch()) {
            $firstLvlSections[$ob_sort['ID']]['UF_B2B_SORT'] = $ob_sort['UF_B2B_SORT'];
        }
        uasort($firstLvlSections, function ($a, $b) {
            if ($a['UF_B2B_SORT'] == $b['UF_B2B_SORT']) {
                return 0;
            }
            return ($a['UF_B2B_SORT'] < $b['UF_B2B_SORT']) ? -1 : 1;
        });

        $arResult['GROUPED_PRODUCTS'] = [];

        foreach ($firstLvlSections as &$pSection) {
            foreach ($ready as $key => &$rItem) {
                if (in_array($rItem['PRODUCT_SECTION_ID'], $pSection['PRODUCT_SECTION_ID'])) {
                    if (!empty($rItem['BREND'])) {
                        $pSection['BASE_PRICE_LEGANDA_TEA'] += $rItem["BASE_PRICE"] * $rItem["QUANTITY"];
                        $arProductNewPrice[] = $rItem['PRODUCT_ID'];
                    }
                    if ($pSection['ID'] == ID_SECTION_MONTIS) {
                        $arProductNewPrice[] = $rItem['PRODUCT_ID'];
                    }
                    $pSection['BASE_PRICE'] += $rItem["BASE_PRICE"] * $rItem["QUANTITY"];
                    $pSection['SUM'] += $rItem["SUM_VALUE"];
                    unset ($rItem);
                }
            }
            $pSection['SUM_FORMAT'] = \SaleFormatCurrency(
                    $pSection['SUM'],
                    'RUB',
                    true
                ) . ' ₽';
        }
        $arResult['GROUPED_PRODUCTS'] = $firstLvlSections;
    }

    /**
     * Для каталога - количество каждого товара в корзине
     */
    $arResult['CATALOG_BASKET'] = [];
    foreach ($arResult["CATEGORIES"]['READY'] as $item) {
        $totalSum += $item['QUANTITY'] * $item['BASE_PRICE'];
        $arResult['CATALOG_BASKET'][$item['PRODUCT_ID']] += $item['QUANTITY'];
    }
}


foreach ($arResult["CATEGORIES"]['READY'] as $item) {
    $arProduct['PRODUCT_ID'][$item['PRODUCT_ID']] = $item['PRODUCT_ID'];
    $arProduct['QUANTITY'][$item['PRODUCT_ID']] = $item['QUANTITY'];
    $arProduct['PRICE'][$item['PRODUCT_ID']] = $item['PRICE'];
    $arProduct['PRICE_TYPE_ID'][$item['PRODUCT_ID']] = $item['PRICE_TYPE_ID'];
}

$result = \Webfly\Handlers\Sale::getPrice($arProduct['PRODUCT_ID'], $arProduct['PRICE'], $arProduct['QUANTITY'], $arProductNewPrice, $arResult["TOTAL_PRICE_RAW"]);
if ($result) {
    $idPrice = $result['PRICE_ID'] + 1;
    $arProductPrice = $result['PRODUCT_PRICE'];
    $sumProductOldPrice = $result['SUM_PRODUCT_OLD_PRICE'];
}

switch ($idPrice) {
    case ID_TYPE1_PRICE_B2B:
        $persentCoffee = '7';
        $persentTea = '3';
        $amountDiscount = 15000;
        break;
    case ID_TYPE2_PRICE_B2B:
        $persentCoffee = '14';
        $persentTea = '6';
        $amountDiscount = 50000;
        break;
    case ID_TYPE3_PRICE_B2B:
        $persentCoffee = '20';
        $persentTea = '10';
        $amountDiscount = 100000;
        break;
    case 28:
        $persentCoffee = '20';
        $persentTea = '10';
        $alert = 'У вас максимальная скидка!';
        $amountDiscount = true;
        break;
    default;
        break;
}
if ($amountDiscount) {
    $arReduse = $amountDiscount - $arProductPrice[$idPrice];
    $arResult["SALE"]['SUM'] = \SaleFormatCurrency($arReduse, 'RUB', true) . ' ₽';
    if ($alert) $arResult["SALE"]['TEXT'] = $alert;
    else $arResult["SALE"]['TEXT'] = 'Добавьте товаров на сумму ' . $arResult["SALE"]['SUM'] . ' и получите скидку: ';

    $arResult["SALE"]['MONTIS']['TEXT'] .= $persentCoffee . '% от цены на кофе «Montis»,';
    $arResult["SALE"]['LEGENDA_TEA']['TEXT'] .= $persentTea . '% от цены на чай бренда «Легенда чая».';
}


if ($totalSum > $arResult["TOTAL_PRICE_RAW"]) {
    $discountSum = $totalSum - $arResult["TOTAL_PRICE_RAW"];
    $arResult["DISCONT_PRICE_FORMAT"] = \SaleFormatCurrency(
            $discountSum,
            'RUB',
            true
        ) . ' ₽';
}

$arResult["TOTAL_PRICE_FORMAT"] = \SaleFormatCurrency(
        $arResult["TOTAL_PRICE_RAW"] ?: 0,
        'RUB',
        true
    ) . ' ₽';
