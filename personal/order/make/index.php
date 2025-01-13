<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>
<?
$comp = "webfly:checkout";

//global $USER;
//if ($USER->GetId() == 2389) {
//    $comp = "bitrix:sale.order.ajax";
//}
$template = '.default';


$APPLICATION->IncludeComponent(
    $comp,
    $template,
    array(
        "PAY_FROM_ACCOUNT" => "N",
        "COUNT_DELIVERY_TAX" => "N",
        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
        "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
        "ALLOW_AUTO_REGISTER" => "N",
        "SEND_NEW_USER_NOTIFY" => "N",
        "DELIVERY_NO_AJAX" => "Y",
        "TEMPLATE_LOCATION" => "popup",
        "PROP_1" => "",
        "PATH_TO_BASKET" => "/personal/cart/",
        "PATH_TO_PERSONAL" => "/personal/orders/",
        "PATH_TO_PAYMENT" => "/personal/order/payment/",
        "PATH_TO_ORDER" => "/personal/order/make/",
        "SET_TITLE" => "Y",
        "SHOW_ACCOUNT_NUMBER" => "Y",
        "DELIVERY_NO_SESSION" => "Y",
        "COMPATIBLE_MODE" => "N",
        "BASKET_POSITION" => "before",
        "BASKET_IMAGES_SCALING" => "adaptive",
        "SERVICES_IMAGES_SCALING" => "adaptive",
        "USER_CONSENT" => "Y",
        "USER_CONSENT_ID" => "1",
        "USER_CONSENT_IS_CHECKED" => "Y",
        "USER_CONSENT_IS_LOADED" => "Y",
        "COMPONENT_TEMPLATE" => $template,
        "ALLOW_APPEND_ORDER" => "N",
        "SHOW_NOT_CALCULATED_DELIVERIES" => "L",
        "SPOT_LOCATION_BY_GEOIP" => "Y",
        "DELIVERY_TO_PAYSYSTEM" => "d2p",
        "SHOW_VAT_PRICE" => "Y",
        "USE_PREPAYMENT" => "N",
        "USE_PRELOAD" => "Y",
        "ALLOW_USER_PROFILES" => "Y",
        "ALLOW_NEW_PROFILE" => "Y",
        "TEMPLATE_THEME" => "blue",
        "SHOW_ORDER_BUTTON" => "final_step",
        "SHOW_TOTAL_ORDER_BUTTON" => "N",
        "SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
        "SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
        "SHOW_DELIVERY_LIST_NAMES" => "Y",
        "SHOW_DELIVERY_INFO_NAME" => "Y",
        "SHOW_DELIVERY_PARENT_NAMES" => "Y",
        "SHOW_STORES_IMAGES" => "N",
        "SKIP_USELESS_BLOCK" => "N",
        "SHOW_BASKET_HEADERS" => "N",
        "DELIVERY_FADE_EXTRA_SERVICES" => "N",
        "SHOW_NEAREST_PICKUP" => "N",
        "DELIVERIES_PER_PAGE" => "9",
        "PAY_SYSTEMS_PER_PAGE" => "9",
        "PICKUPS_PER_PAGE" => "5",
        "SHOW_PICKUP_MAP" => "N",
        "SHOW_MAP_IN_PROPS" => "N",
        "PICKUP_MAP_TYPE" => "yandex",
        "SHOW_COUPONS" => "N",
        "SHOW_COUPONS_BASKET" => "Y",
        "SHOW_COUPONS_DELIVERY" => "N",
        "SHOW_COUPONS_PAY_SYSTEM" => "N",
        "PROPS_FADE_LIST_1" => "",
        "PROPS_FADE_LIST_2" => "",
        "ACTION_VARIABLE" => "soa-action",
        "PATH_TO_AUTH" => "/auth/",
        "DISABLE_BASKET_REDIRECT" => "Y",
        "EMPTY_BASKET_HINT_PATH" => "/catalog/",
        "USE_PHONE_NORMALIZATION" => "Y",
        "PRODUCT_COLUMNS_VISIBLE" => array(
            0 => "PREVIEW_PICTURE",
            1 => "PROPS",
        ),
        "ADDITIONAL_PICT_PROP_2" => "-",
        "ADDITIONAL_PICT_PROP_3" => "-",
        "PRODUCT_COLUMNS_HIDDEN" => "",
        "HIDE_ORDER_DESCRIPTION" => "N",
        "USE_YM_GOALS" => "N",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "USE_CUSTOM_MAIN_MESSAGES" => "N",
        "USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
        "USE_CUSTOM_ERROR_MESSAGES" => "N"
    ),
    false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
