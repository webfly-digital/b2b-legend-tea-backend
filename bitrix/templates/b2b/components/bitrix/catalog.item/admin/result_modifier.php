<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!function_exists('findOfferByProp')) {
    function findOfferByProp($props, $jsOffers)
    {
        $currentOffer = [];
        foreach ($jsOffers as $offerIndex => $offer) {
            $countMatch = 0;
            foreach ($props as $prop => $val) {
                if ($offer['TREE'][$prop] == $val)
                    $countMatch++;
            }
            if ($countMatch == count($props)) {
                $currentOffer = $offer;
                break;
            }
        }
        return ['OFFER' => $currentOffer, 'INDEX' => $offerIndex];
    }
}

$item = $arResult['ITEM'];
$haveOffers = !empty($item['OFFERS']);
if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP'])) {

    $arResult['ITEM']['SEPARATE_OFFERS'] = [];

    $primaryPropertyCode = $item['COLUMNS'][0]['CODE'];
    $secondaryPropertyCode = $item['COLUMNS'][1]['CODE'];


    $activeOffers = [];
    $primaryProperty = $arParams['SKU_PROPS'][$primaryPropertyCode];
    $primaryPropertyId = $primaryProperty['ID'];



    if (isset($item['SKU_TREE_VALUES'][$primaryPropertyId])) {
        foreach ($primaryProperty['VALUES'] as $pKey => $value) {
            $secondaryPropertyId = '';
            $curOfferProperties = [];

            if (!isset($item['SKU_TREE_VALUES'][$primaryPropertyId][$value['ID']]))
                continue;

            $valueId = $value['ID'];
            if ($secondaryPropertyCode) {
                $variants = [];
                $variants = array_filter($item['JS_OFFERS'], function ($v) use ($valueId, $primaryPropertyId) {
                    return $v['TREE']['PROP_' . $primaryPropertyId] == $valueId;
                });

                if ($variants) {
                    $secondary = [];
                    foreach ($variants as $var) {
                        if (!in_array($var['TREE']['PROP_' . $arParams['SKU_PROPS'][$secondaryPropertyCode]['ID']], $secondary))
                            $secondary[] = $var['TREE']['PROP_' . $arParams['SKU_PROPS'][$secondaryPropertyCode]['ID']];
                    }
                }
                $secondaryPropertyId = $arParams['SKU_PROPS'][$secondaryPropertyCode]['ID'];
            }
            $curOfferProperties["PROP_{$primaryPropertyId}"] = $value['ID'];


            if(!empty($secondary)) {
                $selectedPomol = $arParams['POMOL'] ?: '';
                if (!empty($selectedPomol)) {
                    $key = array_keys($secondary, $selectedPomol);
                    if ($key) {
                        unset($secondary[$key[array_key_first($key)]]);
                        array_unshift($secondary, $selectedPomol);
                    }
                }
            }

            if ($secondaryPropertyId && $secondary) {
                foreach ($secondary as $valId) {
                    if (!isset($item['SKU_TREE_VALUES'][$arParams['SKU_PROPS']['POMOL']['ID']][$valId]))
                        continue;

                    if (!$curOfferProperties["PROP_{$secondaryPropertyId}"]) {
                        $curOfferProperties["PROP_{$secondaryPropertyId}"] = $valId;
                    }

                }
            }

            $currentOfferData = findOfferByProp($curOfferProperties, $item['JS_OFFERS']);
            $currentOffer = $currentOfferData['OFFER'];
            $currentOfferIndex = $currentOfferData['INDEX'];

            $separateOfferItem = [
                'ITEM' => $currentOffer,
                'INDEX' => $currentOfferIndex,
                'PROPERTIES' => [
                    'PRIMARY' => ['ID' => $primaryPropertyId, 'VALUE' => $value],
                ],
            ];

            if ($secondaryPropertyCode) {
                $separateOfferItem['PROPERTIES']['SECONDARY'] = ['ID' => $secondaryPropertyId, 'VALUE' => $secondary];
            }

            $arResult['ITEM']['SEPARATE_OFFERS'][] = $separateOfferItem;
        }
        if ($arResult['ITEM']['SEPARATE_OFFERS']) {
            //сортировка ску по цене
            if ($arParams['OFFERS_SORT_FIELD'] == 'PRICE_' . CATALOG_PRICE_ID) {

                $offerSort = $arParams["OFFERS_SORT_ORDER"] ?: 'asc';
                usort($arResult['ITEM']['SEPARATE_OFFERS'], function ($a, $b) use ($offerSort) {
                    $priceA = $a['ITEM']['ITEM_PRICES'][$a['ITEM']['PRICE']];
                    $priceB = $b['ITEM']['ITEM_PRICES'][$b['ITEM']['PRICE']];

                    if ($priceA == $priceB) {
                        return 0;
                    }

                    if ($offerSort == 'asc') {
                        return ($priceA < $priceB) ? -1 : 1;
                    } elseif ($offerSort == 'desc') {
                        return ($priceA > $priceB) ? -1 : 1;
                    }
                });
            }

        }
    }
}
/**
 * Слайдер изображений
 */
$pictures = [];
if ($arResult['ITEM']['DETAIL_PICTURE']['ID'])
    $pictures[] = $arResult['ITEM']['DETAIL_PICTURE']['ID'];
if ($arResult['ITEM']['PROPERTIES']['MORE_PHOTO']['VALUE'])
    $pictures = array_merge($pictures, $arResult['ITEM']['PROPERTIES']['MORE_PHOTO']['VALUE']);

if ($pictures) {
    foreach ($pictures as $pictureID) {
        $arResult['ITEM']['PICTURES'][] = CFile::ResizeImageGet($pictureID, ['width' => 352, 'height' => 352], BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 100);
        $arResult['ITEM']['PICTURES_ORIGIN'][] = CFile::GetPath($pictureID);
    }
}
/**
 * Кислотность, плотность
 */
$ranges = ['KISLOTNOST', 'PLOTNOST'];
foreach ($ranges as $rangeCode) {
    if ($arResult['ITEM']['PROPERTIES'][$rangeCode]['VALUE']) {
        $percent = \Webfly\Helper\Helper::getRangePercent($arResult['ITEM']['PROPERTIES'][$rangeCode]['VALUE']);
        if ($percent > 0)
            $arResult['ITEM']['RANGES'][$rangeCode] = ['NAME' => $arResult['ITEM']['PROPERTIES'][$rangeCode]['NAME'], 'VALUE' => $percent];
    }
}
