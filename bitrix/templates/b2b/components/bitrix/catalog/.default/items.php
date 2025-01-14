<? if (!isset($request)) $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$baseFilter = [
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
    'SECTION_ACTIVE' => 'Y',
    'SECTION_GLOBAL_ACTIVE' => 'Y',
    'INCLUDE_SUBSECTIONS' => 'Y',
    'ACTIVE' => 'Y'
];


global $searchFilter;
if (!empty($searchFilter['ID'])) $arSearchElements = $searchFilter['ID'];


if (!empty($arSearchElements)) {//доп фильтр из результатов поиска
    $baseFilter['ID'] = $arSearchElements;
    $GLOBALS['arrFilter']['ID'] = $arSearchElements;
}
if ($GLOBALS['arrFilter']) {
    $filter = array_merge($baseFilter, $GLOBALS['arrFilter']);
} else {
    $filter = $baseFilter;
}

if (!empty($arSearchElements)) unset($filter["SECTION_CODE"]);
$sections = \Webfly\Helper\Helper::getCatalogSections($filter);



//помол  и вид хранится в информации о пользовател
global $USER;
$userId = $USER->GetId();
if (!empty($userId)) {
    $order = ['sort' => 'asc'];
    $tmp = 'sort';
    $filter = ['ID' => $userId];
    $select = ["UF_VIEW"];
    if (in_array('POMOL', $arParams['OFFER_TREE_PROPS'])) $select[] = "UF_ID_POMOL";
    $rsUsers = CUser::GetList($order, $tmp, $filter, ["SELECT" => $select]);

    while ($arUser = $rsUsers->Fetch()) {
        if (empty($arSearchElements)) {
            $selectedPomol = $arUser['UF_ID_POMOL'];
        }
        $selectedViewID = $arUser['UF_VIEW'];
    }

    if ($selectedViewID) {
        $obEnum = new \CUserFieldEnum;
        $rsEnum = $obEnum->GetList([], ['ID' => $selectedViewID]);
        while ($arEnum = $rsEnum->GetNext()) {
            $selectedView = $arEnum['XML_ID'];
        }
    }
}


//получаем информацию о выводе Срооков поставки в каждом Товаре и ТП
$arParams['DELIVERY_TIME'] = 14;
$filter = ['CODE' => $arResult["VARIABLES"]["SECTION_CODE"], 'IBLOCK_ID' => $arParams['IBLOCK_ID']];
$rsInfo = \CIBlockSection::GetList([], $filter, false, ['UF_DELIVERY_TIME']);
while ($arInfo = $rsInfo->Fetch()) {
    if ($arInfo['UF_DELIVERY_TIME']) $arParams['DELIVERY_TIME'] = $arInfo['UF_DELIVERY_TIME'];
}

$nav = \CIBlockSection::GetNavChain(false, $arResult['VARIABLES']['SECTION_ID']);
$arSection = $nav->GetNext();
if (empty($arSearchElements)) {
//получаем информацию о генерации пдф
    $arParams['GENERATE_PDF'] = false;
    if ($arSection['ID'] && in_array($arSection['ID'], SECTION_GENERATION_PDF)) $arParams['GENERATE_PDF'] = true;
}
global $arrFilter;

if (!empty($arSearchElements) && !empty($sections)) { //есди найдены товары по поиску  и есть разделы, то будет показывать список разделов


    global $arrSectionsFilter;
    $arrSectionsFilter['ID'] = array_keys($sections);

    global $arSearchElementsFilter;
    $arSearchElementsFilter['ID'] = $arSearchElements;

    $queryList = $request->getQueryList()->toArray();
    $pageDirectory = $request->getRequestedPageDirectory();
    $sectionsParams = array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "FILTER_NAME" => 'arrSectionsFilter',
        'ADDITIONAL_COUNT_ELEMENTS_FILTER' => 'arSearchElementsFilter',
        "SECTION_ID" => '',    // ID раздела
        "SECTION_CODE" => "",    // Код раздела
        "COUNT_ELEMENTS" => "Y",    // Показывать количество элементов в разделе
        "COUNT_ELEMENTS_FILTER" => "CNT_ALL",    // Показывать количество
        "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "Y",    // Скрывать разделы с нулевым количеством элементов
        "TOP_DEPTH" => "2",    // Максимальная отображаемая глубина разделов
        "SECTION_FIELDS" => array(    // Поля разделов
            0 => "",
        ),
        "SECTION_USER_FIELDS" => array(    // Свойства разделов
            0 => "",
        ),
        "VIEW_MODE" => "LINE",    // Вид списка подразделов
        "SHOW_PARENT_NAME" => "Y",    // Показывать название раздела
        "SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
        "CACHE_TYPE" => "A",    // Тип кеширования
        "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
        "CACHE_GROUPS" => "Y",    // Учитывать права доступа
        "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
        "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
        "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
        "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
        'QUERY_LIST' => $queryList,
        'PAGE_DIR' => $pageDirectory
    );
    $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "search_catalog", $sectionsParams, false);

    if (!empty($queryList['sections'])) $arrFilter['IBLOCK_SECTION_ID'] = $queryList['sections'];

}



$sectionParams = array(
    'SECTION_MAIN' => $arSection,
    'USER_ID' => $arParams["USER_ID"],
    'GENERATE_PDF' => $arParams['GENERATE_PDF'],
    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "ELEMENT_SORT_FIELD" => $request->get('sort') ?: $arParams["ELEMENT_SORT_FIELD"],
    "ELEMENT_SORT_ORDER" => $request->get('order') ?: $arParams["ELEMENT_SORT_ORDER"],
    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
    "PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
    "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
    "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
    "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
    "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
    "INCLUDE_SUBSECTIONS" => !empty($arSearchElements) ? "Y" : $arParams["INCLUDE_SUBSECTIONS"],
    "BASKET_URL" => $arParams["BASKET_URL"],
    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
    "SECTION_ID_VARIABLE" => !empty($arSearchElements) ? "" : $arParams["SECTION_ID_VARIABLE"],
    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
    "FILTER_NAME" => $arParams["FILTER_NAME"],
    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    "SET_TITLE" => $arParams["SET_TITLE"],
    "MESSAGE_404" => $arParams["~MESSAGE_404"],
    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
    "SHOW_404" => $arParams["SHOW_404"],
    "FILE_404" => $arParams["FILE_404"],
    "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
    "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
    "PRICE_CODE" => $arParams["~PRICE_CODE"],
    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
    "PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
    "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
    "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
    "LAZY_LOAD" => $arParams["LAZY_LOAD"],
    "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
    "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

    "OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
    "OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
    "OFFERS_SORT_FIELD" => $request->get('sort') ?: $arParams["OFFERS_SORT_FIELD"],
    "OFFERS_SORT_ORDER" => $request->get('order') ?: $arParams["OFFERS_SORT_ORDER"],
    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
    "OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

    "SHOW_ALL_WO_SECTION" => "",//!empty($arSearchElements) ? "Y" : "",
    "SECTION_ID" => !empty($arSearchElements) ? '' : (current($sections)['ID'] ?: $arResult["VARIABLES"]["SECTION_ID"]),
    "SECTION_CODE" => !empty($arSearchElements) ? "" : (current($sections)['CODE'] ?: $arResult["VARIABLES"]["SECTION_CODE"]),
    "SECTION_URL" => !empty($arSearchElements) ? "" : ($arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"]),
    "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
    "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

    'LABEL_PROP' => $arParams['LABEL_PROP'],
    'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
    'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
    'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
    'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
    'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
    'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
    'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
    'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
    'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
    'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
    'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
    'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
    'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
    'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
    'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
    'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
    'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
    'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
    'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

    'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
    'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
    'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),
    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
    "ADD_SECTIONS_CHAIN" => "N",
    'ADD_TO_BASKET_ACTION' => $basketAction,
    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
    'COMPARE_PATH' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['compare'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
    'USE_COMPARE_LIST' => 'Y',
    'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
    'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
    'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
    'SECTIONS' => $sections ?: [],
    'SORT' => $request->get('sort') && $request->get('order') ? 'Y' : 'N',
    'POMOL' => $selectedPomol ?: '',
    'VIEW' => $selectedView ?: 'image',
    'DELIVERY_TIME' => $arParams['DELIVERY_TIME'],
    'SEARCH' => !empty($arSearchElements) ? 'Y' : "N"
);

unset($sectionParams["PRICE_CODE"]);
$sectionParams["PRICE_CODE"][] = 'ОПТОВЫЙ КАБИНЕТ с НДС';
if ($_REQUEST["price"]) {
    unset($sectionParams["PRICE_CODE"]);
    if ($_REQUEST["price"] == ID_TYPE1_PRICE_B2B) {
        $sectionParams["PRICE_CODE"][] = '2 от 15.000 руб. с НДС';
    } elseif ($_REQUEST["price"] == ID_TYPE2_PRICE_B2B) {
        $sectionParams["PRICE_CODE"][] = '3 от 50.000 руб. с НДС';
    } elseif ($_REQUEST["price"] == ID_TYPE3_PRICE_B2B) {
        $sectionParams["PRICE_CODE"][] = '4 от 100.000 руб. с НДС';
    }
}


$intSectionID = $APPLICATION->IncludeComponent("bitrix:catalog.section", '', $sectionParams, $component);

$sectionParams['SECTION_ID'] = $arResult['VARIABLES']['SECTION_ID'];
$sectionParams['PAGE_ELEMENT_COUNT'] = 0;
$sectionParams['ADD_SECTIONS_CHAIN'] = 'Y';
$APPLICATION->IncludeComponent("bitrix:catalog.section", "empty", $sectionParams, $component);

$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
unset($basketAction);

?>
