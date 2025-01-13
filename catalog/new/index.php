<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новинки"); ?>
<div class="three-cols-table-container">
    <div class="left">
    </div>
    <div class="mid">
        <!--Новинки-->
        <?
        global $newFilter;
        $newFilter = ['PROPERTY_HIT_VALUE' => 'Новинка'];

        $baseFilter = [
            'IBLOCK_ID' => CATALOG_IBLOCK_ID,
            'SECTION_ACTIVE' => 'Y',
            'SECTION_GLOBAL_ACTIVE' => 'Y',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'ACTIVE' => 'Y'
        ];
        \Webfly\Helper\Functions::changeFilterOpt($baseFilter);
        $filter = array_merge($newFilter, $baseFilter);
        $sections = \Webfly\Helper\Helper::getCatalogSections($filter);

        $APPLICATION->IncludeComponent('bitrix:catalog.section', '', array(
            'IBLOCK_TYPE' => '1c_catalog',
            'IBLOCK_ID' => '93',
            'ELEMENT_SORT_FIELD' => 'NAME',
            'ELEMENT_SORT_ORDER' => 'asc',
            'ELEMENT_SORT_FIELD2' => 'sort',
            'ELEMENT_SORT_ORDER2' => 'asc',
            'PROPERTY_CODE' =>
                array(
                    0 => 'HIT',
                    1 => 'CML2_ARTICLE',
                    2 => '',
                ),
            'PROPERTY_CODE_MOBILE' =>
                array(),
            'META_KEYWORDS' => '-',
            'META_DESCRIPTION' => '-',
            'BROWSER_TITLE' => '-',
            'SET_LAST_MODIFIED' => 'N',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'BASKET_URL' => '/personal/cart/',
            'ACTION_VARIABLE' => 'action',
            'PRODUCT_ID_VARIABLE' => 'id',
            'SECTION_ID_VARIABLE' => 'SECTION_ID',
            'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
            'PRODUCT_PROPS_VARIABLE' => 'prop',
            'FILTER_NAME' => 'newFilter',
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => '36000000',
            'CACHE_FILTER' => 'Y',
            'CACHE_GROUPS' => 'Y',
            'SET_TITLE' => 'N',
            'MESSAGE_404' => '',
            'SET_STATUS_404' => 'Y',
            'SHOW_404' => 'N',
            'FILE_404' => '',
            'DISPLAY_COMPARE' => 'N',
            'PAGE_ELEMENT_COUNT' => '10',
            'LINE_ELEMENT_COUNT' => '3',
            'PRICE_CODE' =>
                array(
                    0 => 'ОПТОВЫЙ КАБИНЕТ с НДС',
                ),
            'USE_PRICE_COUNT' => 'N',
            'SHOW_PRICE_COUNT' => '1',
            'PRICE_VAT_INCLUDE' => 'Y',
            'USE_PRODUCT_QUANTITY' => 'Y',
            'ADD_PROPERTIES_TO_BASKET' => 'Y',
            'PARTIAL_PRODUCT_PROPERTIES' => 'Y',
            'PRODUCT_PROPERTIES' =>
                array(),
            'DISPLAY_TOP_PAGER' => 'N',
            'DISPLAY_BOTTOM_PAGER' => 'Y',
            'PAGER_TITLE' => 'Товары',
            'PAGER_SHOW_ALWAYS' => 'N',
            "PAGER_TEMPLATE" => ".default",
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000000',
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_BASE_LINK' => NULL,
            'PAGER_PARAMS_NAME' => NULL,
            'LAZY_LOAD' => 'Y',
            'MESS_BTN_LAZY_LOAD' => 'Показать ещё',
            'LOAD_ON_SCROLL' => 'N',
            'OFFERS_CART_PROPERTIES' =>
                array(
                    0 => 'UPAKOVKA',
                    1 => 'POMOL',
                    2 => 'OBEM',
                ),
            'OFFERS_FIELD_CODE' =>
                array(
                    0 => '',
                    1 => '',
                ),
            'OFFERS_PROPERTY_CODE' =>
                array(
                    0 => 'UPAKOVKA',
                    1 => 'POMOL',
                    2 => 'OBEM',
                ),
            'OFFERS_SORT_FIELD' => 'sort',
            'OFFERS_SORT_ORDER' => 'desc',
            'OFFERS_SORT_FIELD2' => 'id',
            'OFFERS_SORT_ORDER2' => 'desc',
            'OFFERS_LIMIT' => '0',
//            'SECTION_ID' => current($sections)['ID'],
//            'SECTION_CODE' => current($sections)['CODE'],
            'SECTION_URL' => '/catalog/#SECTION_CODE#/',
            'DETAIL_URL' => '/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
            'USE_MAIN_ELEMENT_SECTION' => 'N',
            'CONVERT_CURRENCY' => 'Y',
            'CURRENCY_ID' => 'RUB',
            'HIDE_NOT_AVAILABLE' => 'L',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'LABEL_PROP' =>
                array(),
            'LABEL_PROP_MOBILE' => '',
            'LABEL_PROP_POSITION' => 'top-left',
            'ADD_PICT_PROP' => 'MORE_PHOTO',
            'PRODUCT_DISPLAY_MODE' => 'Y',
            'PRODUCT_BLOCKS_ORDER' => 'price,props,sku,quantityLimit,quantity,buttons',
            'PRODUCT_ROW_VARIANTS' => '[{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false}]',
            'ENLARGE_PRODUCT' => 'STRICT',
            'ENLARGE_PROP' => '',
            'SHOW_SLIDER' => 'N',
            'SLIDER_INTERVAL' => '3000',
            'SLIDER_PROGRESS' => 'N',
            'OFFER_ADD_PICT_PROP' => 'MORE_PHOTO',
            'OFFER_TREE_PROPS' =>
                array(
                    0 => 'UPAKOVKA',
                    1 => 'POMOL',
                    2 => 'OBEM',
                ),
            'PRODUCT_SUBSCRIPTION' => 'Y',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'DISCOUNT_PERCENT_POSITION' => 'bottom-right',
            'SHOW_OLD_PRICE' => 'N',
            'SHOW_MAX_QUANTITY' => 'N',
            'MESS_SHOW_MAX_QUANTITY' => '',
            'RELATIVE_QUANTITY_FACTOR' => '',
            'MESS_RELATIVE_QUANTITY_MANY' => '',
            'MESS_RELATIVE_QUANTITY_FEW' => '',
            'MESS_BTN_BUY' => 'Купить',
            'MESS_BTN_ADD_TO_BASKET' => 'В корзину',
            'MESS_BTN_SUBSCRIBE' => 'Уведомить о поступлении',
            'MESS_BTN_DETAIL' => 'Подробнее',
            'MESS_NOT_AVAILABLE' => 'Нет в наличии',
            'MESS_BTN_COMPARE' => 'Сравнение',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'DATA_LAYER_NAME' => '',
            'BRAND_PROPERTY' => '',
            'TEMPLATE_THEME' => 'site',
            'ADD_SECTIONS_CHAIN' => 'N',
            'ADD_TO_BASKET_ACTION' => 'ADD',
            'SHOW_CLOSE_POPUP' => 'N',
            'COMPARE_PATH' => '/catalog/compare/',
            'COMPARE_NAME' => NULL,
            'USE_COMPARE_LIST' => 'Y',
            'BACKGROUND_IMAGE' => '-',
            'COMPATIBLE_MODE' => 'N',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
            'SECTIONS' => $sections,
            'NEW' => 'Y'
        )); ?>
    </div>
    <div class="right">
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
