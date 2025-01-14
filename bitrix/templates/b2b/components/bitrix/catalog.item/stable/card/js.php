<?
$addToBasketUrl = SITE_TEMPLATE_PATH.'/ajax/basket.php';
if (!$haveOffers) {
    $jsParams = array(
        'PRODUCT_TYPE' => $item['PRODUCT']['TYPE'],
        'USE_SUBSCRIBE' => $showSubscribe,
        'PRODUCT_DISPLAY_MODE'=>$arParams['PRODUCT_DISPLAY_MODE'],
        'USE_ENHANCED_ECOMMERCE'=>$arParams['USE_ENHANCED_ECOMMERCE'],
        'DATA_LAYER_NAME'=>$arParams['DATA_LAYER_NAME'],
        'PRODUCT' => array(
            'ID' => $item['ID'],
            'NAME' => $productTitle,
            'PARENT_ID'=>$item['ID'],
            'CAN_BUY' => $item['CAN_BUY'],
            'CHECK_QUANTITY' => $item['CHECK_QUANTITY'],
            'MAX_QUANTITY' => $item['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $item['ITEM_MEASURE_RATIOS'][$item['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'ITEM_PRICES' => $item['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED' => $item['ITEM_PRICE_SELECTED'],
        ),
        'BASKET' => array(
            'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => empty($item['PRODUCT_PROPERTIES']),
            'ADD_TO_BASKET_URL' => $addToBasketUrl
        ),
        'VISUAL' => array(
            'ID' => $itemIds['ID'],
            'QUANTITY_ID' => $itemIds['QUANTITY'],
            'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP'],
            'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN'],
            'PRICE_ID' => $itemIds['PRICE'],
            'NOT_AVAILABLE_MESS' => $itemIds['NOT_AVAILABLE_MESS'],
            'SUBSCRIBE_ID' => $itemIds['SUBSCRIBE_LINK'],
        )
    );
    $additionalJsParams = [
        'PRODUCT' => [
            'ID' => $item['ID'],
        ],
        'FAVORITE_BTN' => $itemIds['FAVORITE_BTN'],
        'DETAIL_BTN' => $itemIds['DETAIL_BTN'],
        'DETAIL_BLOCK' => $itemIds['DETAIL_BLOCK'],
        'SUBSCRIBE_LINK_HIDDEN' => $itemIds['SUBSCRIBE_LINK_HIDDEN'],
    ];
} else {
    if ($item['SEPARATE_OFFERS']){
        foreach ($item['SEPARATE_OFFERS'] as $activeOffer){
            $jsParams[$activeOffer['ITEM']['ID']] = array(
                'PRODUCT_TYPE' => $item['PRODUCT']['TYPE'],
                'PRODUCT_DISPLAY_MODE'=>$arParams['PRODUCT_DISPLAY_MODE'],
                'USE_ENHANCED_ECOMMERCE'=>$arParams['USE_ENHANCED_ECOMMERCE'],
                'DATA_LAYER_NAME'=>$arParams['DATA_LAYER_NAME'],
                'PRODUCT' => array(
                    'ID' => $activeOffer['ITEM']['ID'],
                    'NAME' => $productTitle,
                    'PARENT_ID'=>$item['ID']
                ),
                'BASKET' => array(
                    'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                    'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                    'SKU_PROPS' => $item['OFFERS_PROP_CODES'],
                    'ADD_TO_BASKET_URL' => $addToBasketUrl
                ),
                'VISUAL' => array(
                    'ID' => $itemIds['ID'],
                    'QUANTITY_ID' => $itemIds['QUANTITY']."_offer_".$activeOffer['ITEM']['ID'],
                    'QUANTITY_UP_ID' => $itemIds['QUANTITY_UP']."_offer_".$activeOffer['ITEM']['ID'],
                    'QUANTITY_DOWN_ID' => $itemIds['QUANTITY_DOWN']."_offer_".$activeOffer['ITEM']['ID'],
                    'PRICE_ID' => $itemIds['PRICE']."_offer_".$activeOffer['ITEM']['ID'],
                    'TREE_ID' => $itemIds['PROP_DIV']."_offer_".$activeOffer['ITEM']['ID'],
                    'TREE_ITEM_ID' => $itemIds['PROP'],
                    'SUBSCRIBE_ID' => $itemIds['SUBSCRIBE_LINK'],
                ),
                'OFFERS' => array(),
                'OFFER_SELECTED' => 0,
                'TREE_PROPS' => array()
            );

            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP'])) {
                $jsParams[$activeOffer['ITEM']['ID']]['SHOW_SKU_PROPS'] = $item['OFFERS_PROPS_DISPLAY'];
                $jsParams[$activeOffer['ITEM']['ID']]['OFFERS'] = $item['JS_OFFERS'];
                $jsParams[$activeOffer['ITEM']['ID']]['OFFER_SELECTED'] = $activeOffer['INDEX'];
                $jsParams[$activeOffer['ITEM']['ID']]['TREE_PROPS'] = $skuProps;
            }
        }
        $additionalJsParams = [
            'PRODUCT' => [
                'ID' => $item['ID'],
            ],
            'FAVORITE_BTN' => $itemIds['FAVORITE_BTN'],
            'DETAIL_BTN' => $itemIds['DETAIL_BTN'],
            'DETAIL_BLOCK' => $itemIds['DETAIL_BLOCK']
        ];
    }

}
