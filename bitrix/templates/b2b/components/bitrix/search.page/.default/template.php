<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arSectionParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

CModule::includeModule('iblock');

if (isset($arSectionParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arSectionParams['USE_COMMON_SETTINGS_BASKET_POPUP'] === 'Y') {
    $basketAction = isset($arSectionParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arSectionParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
} else {
    $basketAction = isset($arSectionParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arSectionParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}
$arSectionParams = $arParams["PROXY"];

unset($arSectionParams["PRICE_CODE"]);
$sectionParams["PRICE_CODE"][] = 'ОПТОВЫЙ КАБИНЕТ с НДС';

if ($_REQUEST["price"]) {
    unset($arSectionParams["PRICE_CODE"]);
    if ($_REQUEST["price"] == ID_TYPE1_PRICE_B2B) {
        $arSectionParams["PRICE_CODE"][] = '2 от 15.000 руб. с НДС';
    } elseif ($_REQUEST["price"] == ID_TYPE2_PRICE_B2B) {
        $arSectionParams["PRICE_CODE"][] = '3 от 50.000 руб. с НДС';
    } elseif ($_REQUEST["price"] == ID_TYPE3_PRICE_B2B) {
        $arSectionParams["PRICE_CODE"][] = '4 от 100.000 руб. с НДС';
    }
}

global $USER;
$userId = $USER->GetId();
if (!empty($userId)) { //помол  и вид хранится в информации о пользовател
    $order = ['sort' => 'asc'];
    $tmp = 'sort';
    $filter = ['ID' => $userId];
    $select = ["UF_VIEW"];
    if (in_array('POMOL', $arParams['PROXY']['OFFER_TREE_PROPS'])) $select[] = "UF_ID_POMOL";
    $rsUsers = CUser::GetList($order, $tmp, $filter, ["SELECT" => $select]);

    while ($arUser = $rsUsers->Fetch()) {
        $selectedPomol = $arUser['UF_ID_POMOL'];
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


$arResultShow = [];

if (!empty($arResult["SEARCH"])) {
    foreach ($arResult["SEARCH"] as $res) {
        $arResultElements[$res["ITEM_ID"]] = $res["ITEM_ID"];
        if ($res["PARAM1"] == "1c_catalog" and $res["PARAM2"] == 93) {

            $arSelect = array("ID", "IBLOCK_SECTION_ID");
            $arFilter = array("IBLOCK_ID" => $res["PARAM2"], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "ID" => $res["ITEM_ID"]);
            $rs = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
            while ($ob = $rs->GetNextElement()) {
                $arFields = $ob->GetFields();

                $arFilter2 = array('IBLOCK_ID' => $res["PARAM2"], 'GLOBAL_ACTIVE' => 'Y', 'ID' => $arFields["IBLOCK_SECTION_ID"]);
                $db_list2 = CIBlockSection::GetList(array(), $arFilter2, false);
                while ($ar_result2 = $db_list2->GetNext()) {
                    $arResultShow[$ar_result2["ID"]][$res["ITEM_ID"]] = $res;
                    $arResultSections[$ar_result2["ID"]] = $ar_result2;
                }
            }
        }
    }
}

?>


<? if (!empty($arResultSections)) {
    global $arrFilter;
    $arrFilter["ID"] = $arResultElements;
    $sectionParams = array(
        'GENERATE_PDF' => $arSectionParams['GENERATE_PDF'],
        "IBLOCK_TYPE" => $arSectionParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arSectionParams["IBLOCK_ID"],
        "ELEMENT_SORT_FIELD" => $_REQUEST['sort'] ?: $arSectionParams["ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => $_REQUEST['order'] ?: $arSectionParams["ELEMENT_SORT_ORDER"],
        "ELEMENT_SORT_FIELD2" => $arSectionParams["ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arSectionParams["ELEMENT_SORT_ORDER2"],
        "PROPERTY_CODE" => (isset($arSectionParams["LIST_PROPERTY_CODE"]) ? $arSectionParams["LIST_PROPERTY_CODE"] : []),
        "PROPERTY_CODE_MOBILE" => $arSectionParams["LIST_PROPERTY_CODE_MOBILE"],
        "META_KEYWORDS" => $arSectionParams["LIST_META_KEYWORDS"],
        "META_DESCRIPTION" => $arSectionParams["LIST_META_DESCRIPTION"],
        "BROWSER_TITLE" => $arSectionParams["LIST_BROWSER_TITLE"],
        "SET_LAST_MODIFIED" => $arSectionParams["SET_LAST_MODIFIED"],
        "INCLUDE_SUBSECTIONS" => $arSectionParams["INCLUDE_SUBSECTIONS"],
        "BASKET_URL" => $arSectionParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arSectionParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arSectionParams["PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => !empty($arSearchElements) ? "" : $arSectionParams["SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arSectionParams["PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arSectionParams["PRODUCT_PROPS_VARIABLE"],
        "FILTER_NAME" => $arSectionParams["FILTER_NAME"],
        "CACHE_TYPE" => $arSectionParams["CACHE_TYPE"],
        "CACHE_TIME" => $arSectionParams["CACHE_TIME"],
        "CACHE_FILTER" => $arSectionParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arSectionParams["CACHE_GROUPS"],
        "SET_TITLE" => "N",
        "MESSAGE_404" => $arSectionParams["~MESSAGE_404"],
        "SET_STATUS_404" => "N",
        "SHOW_404" => $arSectionParams["SHOW_404"],
        "FILE_404" => $arSectionParams["FILE_404"],
        "DISPLAY_COMPARE" => $arSectionParams["USE_COMPARE"],
        "PAGE_ELEMENT_COUNT" => $arSectionParams["PAGE_ELEMENT_COUNT"],
        "LINE_ELEMENT_COUNT" => $arSectionParams["LINE_ELEMENT_COUNT"],
        "PRICE_CODE" => $arSectionParams["PRICE_CODE"],
        "USE_PRICE_COUNT" => $arSectionParams["USE_PRICE_COUNT"],
        "SHOW_PRICE_COUNT" => $arSectionParams["SHOW_PRICE_COUNT"],

        "PRICE_VAT_INCLUDE" => $arSectionParams["PRICE_VAT_INCLUDE"],
        "USE_PRODUCT_QUANTITY" => $arSectionParams['USE_PRODUCT_QUANTITY'],
        "ADD_PROPERTIES_TO_BASKET" => (isset($arSectionParams["ADD_PROPERTIES_TO_BASKET"]) ? $arSectionParams["ADD_PROPERTIES_TO_BASKET"] : ''),
        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arSectionParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arSectionParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
        "PRODUCT_PROPERTIES" => (isset($arSectionParams["PRODUCT_PROPERTIES"]) ? $arSectionParams["PRODUCT_PROPERTIES"] : []),

        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => $arSectionParams["PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => $arSectionParams["PAGER_SHOW_ALWAYS"],
        "PAGER_TEMPLATE" => $arSectionParams["PAGER_TEMPLATE"],
        "PAGER_DESC_NUMBERING" => $arSectionParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arSectionParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL" => $arSectionParams["PAGER_SHOW_ALL"],
        "PAGER_BASE_LINK_ENABLE" => $arSectionParams["PAGER_BASE_LINK_ENABLE"],
        "PAGER_BASE_LINK" => $arSectionParams["PAGER_BASE_LINK"],
        "PAGER_PARAMS_NAME" => $arSectionParams["PAGER_PARAMS_NAME"],
        "LAZY_LOAD" => $arSectionParams["LAZY_LOAD"],
        "MESS_BTN_LAZY_LOAD" => $arSectionParams["~MESS_BTN_LAZY_LOAD"],
        "LOAD_ON_SCROLL" => $arSectionParams["LOAD_ON_SCROLL"],

        "OFFERS_CART_PROPERTIES" => (isset($arSectionParams["OFFERS_CART_PROPERTIES"]) ? $arSectionParams["OFFERS_CART_PROPERTIES"] : []),
        "OFFERS_FIELD_CODE" => $arSectionParams["LIST_OFFERS_FIELD_CODE"],
        "OFFERS_PROPERTY_CODE" => (isset($arSectionParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arSectionParams["LIST_OFFERS_PROPERTY_CODE"] : []),
        "OFFERS_SORT_FIELD" => $_REQUEST['sort'] ?: $arSectionParams["OFFERS_SORT_FIELD"],
        "OFFERS_SORT_ORDER" => $_REQUEST['order'] ?: $arSectionParams["OFFERS_SORT_ORDER"],
        "OFFERS_SORT_FIELD2" => $arSectionParams["OFFERS_SORT_FIELD2"],
        "OFFERS_SORT_ORDER2" => $arSectionParams["OFFERS_SORT_ORDER2"],
        "OFFERS_LIMIT" => (isset($arSectionParams["LIST_OFFERS_LIMIT"]) ? $arSectionParams["LIST_OFFERS_LIMIT"] : 0),

        "SHOW_ALL_WO_SECTION" => !empty($arSearchElements) ? "Y" : "",
        "SECTION_ID" => $k,
        "SECTION_CODE" => '',
        "SECTION_URL" => '/catalog/#SECTION_CODE#/',
        "DETAIL_URL" => '/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
        "USE_MAIN_ELEMENT_SECTION" => $arSectionParams["USE_MAIN_ELEMENT_SECTION"],
        'CONVERT_CURRENCY' => $arSectionParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arSectionParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => "L",
        'HIDE_NOT_AVAILABLE_OFFERS' => $arSectionParams["HIDE_NOT_AVAILABLE_OFFERS"],

        'LABEL_PROP' => $arSectionParams['LABEL_PROP'],
        'LABEL_PROP_MOBILE' => $arSectionParams['LABEL_PROP_MOBILE'],
        'LABEL_PROP_POSITION' => $arSectionParams['LABEL_PROP_POSITION'],
        'ADD_PICT_PROP' => $arSectionParams['ADD_PICT_PROP'],
        'PRODUCT_DISPLAY_MODE' => $arSectionParams['PRODUCT_DISPLAY_MODE'],
        'PRODUCT_BLOCKS_ORDER' => $arSectionParams['LIST_PRODUCT_BLOCKS_ORDER'],
        'PRODUCT_ROW_VARIANTS' => $arSectionParams['LIST_PRODUCT_ROW_VARIANTS'],
        'ENLARGE_PRODUCT' => $arSectionParams['LIST_ENLARGE_PRODUCT'],
        'ENLARGE_PROP' => isset($arSectionParams['LIST_ENLARGE_PROP']) ? $arSectionParams['LIST_ENLARGE_PROP'] : '',
        'SHOW_SLIDER' => $arSectionParams['LIST_SHOW_SLIDER'],
        'SLIDER_INTERVAL' => isset($arSectionParams['LIST_SLIDER_INTERVAL']) ? $arSectionParams['LIST_SLIDER_INTERVAL'] : '',
        'SLIDER_PROGRESS' => isset($arSectionParams['LIST_SLIDER_PROGRESS']) ? $arSectionParams['LIST_SLIDER_PROGRESS'] : '',

        'OFFER_ADD_PICT_PROP' => $arSectionParams['OFFER_ADD_PICT_PROP'],
        'OFFER_TREE_PROPS' => (isset($arSectionParams['OFFER_TREE_PROPS']) ? $arSectionParams['OFFER_TREE_PROPS'] : []),
        'PRODUCT_SUBSCRIPTION' => $arSectionParams['PRODUCT_SUBSCRIPTION'],
        'SHOW_DISCOUNT_PERCENT' => $arSectionParams['SHOW_DISCOUNT_PERCENT'],
        'DISCOUNT_PERCENT_POSITION' => $arSectionParams['DISCOUNT_PERCENT_POSITION'],
        'SHOW_OLD_PRICE' => $arSectionParams['SHOW_OLD_PRICE'],
        'SHOW_MAX_QUANTITY' => $arSectionParams['SHOW_MAX_QUANTITY'],
        'MESS_SHOW_MAX_QUANTITY' => (isset($arSectionParams['~MESS_SHOW_MAX_QUANTITY']) ? $arSectionParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
        'RELATIVE_QUANTITY_FACTOR' => (isset($arSectionParams['RELATIVE_QUANTITY_FACTOR']) ? $arSectionParams['RELATIVE_QUANTITY_FACTOR'] : ''),
        'MESS_RELATIVE_QUANTITY_MANY' => (isset($arSectionParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arSectionParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
        'MESS_RELATIVE_QUANTITY_FEW' => (isset($arSectionParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arSectionParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
        'MESS_BTN_BUY' => (isset($arSectionParams['~MESS_BTN_BUY']) ? $arSectionParams['~MESS_BTN_BUY'] : ''),
        'MESS_BTN_ADD_TO_BASKET' => (isset($arSectionParams['~MESS_BTN_ADD_TO_BASKET']) ? $arSectionParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
        'MESS_BTN_SUBSCRIBE' => (isset($arSectionParams['~MESS_BTN_SUBSCRIBE']) ? $arSectionParams['~MESS_BTN_SUBSCRIBE'] : ''),
        'MESS_BTN_DETAIL' => (isset($arSectionParams['~MESS_BTN_DETAIL']) ? $arSectionParams['~MESS_BTN_DETAIL'] : ''),
        'MESS_NOT_AVAILABLE' => (isset($arSectionParams['~MESS_NOT_AVAILABLE']) ? $arSectionParams['~MESS_NOT_AVAILABLE'] : ''),
        'MESS_BTN_COMPARE' => (isset($arSectionParams['~MESS_BTN_COMPARE']) ? $arSectionParams['~MESS_BTN_COMPARE'] : ''),

        'USE_ENHANCED_ECOMMERCE' => (isset($arSectionParams['USE_ENHANCED_ECOMMERCE']) ? $arSectionParams['USE_ENHANCED_ECOMMERCE'] : ''),
        'DATA_LAYER_NAME' => (isset($arSectionParams['DATA_LAYER_NAME']) ? $arSectionParams['DATA_LAYER_NAME'] : ''),
        'BRAND_PROPERTY' => (isset($arSectionParams['BRAND_PROPERTY']) ? $arSectionParams['BRAND_PROPERTY'] : ''),
        'TEMPLATE_THEME' => (isset($arSectionParams['TEMPLATE_THEME']) ? $arSectionParams['TEMPLATE_THEME'] : ''),
        "ADD_SECTIONS_CHAIN" => "N",
        'ADD_TO_BASKET_ACTION' => "ADD",
        'SHOW_CLOSE_POPUP' => isset($arSectionParams['COMMON_SHOW_CLOSE_POPUP']) ? $arSectionParams['COMMON_SHOW_CLOSE_POPUP'] : '',
        'COMPARE_PATH' => "/catalog/compare/",
        'COMPARE_NAME' => $arSectionParams['COMPARE_NAME'],
        'USE_COMPARE_LIST' => 'Y',
        'BACKGROUND_IMAGE' => (isset($arSectionParams['SECTION_BACKGROUND_IMAGE']) ? $arSectionParams['SECTION_BACKGROUND_IMAGE'] : ''),
        'COMPATIBLE_MODE' => (isset($arSectionParams['COMPATIBLE_MODE']) ? $arSectionParams['COMPATIBLE_MODE'] : ''),
        'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arSectionParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arSectionParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
        'SECTIONS' => $arResultSections,
        'SORT' => $_REQUEST['sort'] && $_REQUEST['order'] ? 'Y' : 'N',
        'POMOL' => $selectedPomol ? $selectedPomol : '',
        'VIEW' => $selectedView ? $selectedView : 'image',
        'DELIVERY_TIME' => $arSectionParams['DELIVERY_TIME']
    );
    $APPLICATION->IncludeComponent("bitrix:catalog.section", "", $sectionParams, $component);

} ?>

<? /*</div>*/ ?>
