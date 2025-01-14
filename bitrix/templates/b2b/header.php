<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);

use Bitrix\Main\Page\Asset;

global $USER;
$fields['USER_ID'] = $USER->GetId();
\Webfly\Handlers\Main::OnAfterUserLoginHandler($fields);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" href="/favicon.png" type="image/png">
    <?

    global $USER;
    if ($USER->GetID() == 2389) {
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/admin_assets/style/style.css");
    } else {
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/style/style.css");
    } ?>
    <? $APPLICATION->ShowHead(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <style>
        body {
            font-family: Arial;
        }

        .product-table {
            border: 0;
        }

        .pagination {
            padding-top: 20px;
            border-top: 2px solid #c6c6c6;
        }
    </style>
</head>
<body>
<div class="shade disable"></div>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<header class="<? $APPLICATION->ShowProperty("template_class") ?>">
    <div class="top">
        <div class="left">
            <div class="block">
                <div class="logo">
                    <a href="/">
                        <img class="desc" src="<?= SITE_TEMPLATE_PATH ?>/assets/static/img/logo.png">
                        <img class="mobile" src="<?= SITE_TEMPLATE_PATH ?>/assets/static/img/logo-notext.png">
                    </a>
                </div>
            </div>
            <div class="block d-desktop">
                <a href="tel:<?= file_get_contents(SITE_DIR . "include/header/phone.php") ?>">
                    <h5>
                        <? $APPLICATION->IncludeFile(SITE_DIR . "include/header/phone.php", array(), array("MODE" => "text", "NAME" => "телефон")); ?>
                    </h5>
                </a>
            </div>
            <div class="block d-desktop">
                <div class="time">
                    <div class="icon icon-time"></div>
                    <span>
                        <? $APPLICATION->IncludeFile(SITE_DIR . "include/header/schedule.php", array(), array("MODE" => "text", "NAME" => "режим работы")); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="right">
            <?php
            if ($USER->IsAuthorized()):?>
                <?
                $APPLICATION->IncludeComponent("bitrix:menu", "header", array(
                    "COMPONENT_TEMPLATE" => "horizontal_multilevel",
                    "ROOT_MENU_TYPE" => "header",    // Тип меню для первого уровня
                    "MENU_CACHE_TYPE" => "A",    // Тип кеширования
                    "MENU_CACHE_TIME" => "360000",    // Время кеширования (сек.)
                    "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                    "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                    "MAX_LEVEL" => "2",    // Уровень вложенности меню
                    "CHILD_MENU_TYPE" => "personal",    // Тип меню для остальных уровней
                    "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                    "DELAY" => "N",    // Откладывать выполнение шаблона меню
                    "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                    "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
                    "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
                ),
                    false
                );
                ?>
                <?php
                if (!CSite::InDir('/personal/order/make/')):?>
                    <? $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "small", array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",    // Страница корзины
                        "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",    // Страница оформления заказа
                        "SHOW_NUM_PRODUCTS" => "N",    // Показывать количество товаров
                        "SHOW_TOTAL_PRICE" => "Y",    // Показывать общую сумму по товарам
                        "SHOW_EMPTY_VALUES" => "N",    // Выводить нулевые значения в пустой корзине
                        "SHOW_PERSONAL_LINK" => "N",    // Отображать персональный раздел
                        "PATH_TO_PERSONAL" => SITE_DIR . "personal/",    // Страница персонального раздела
                        "SHOW_AUTHOR" => "N",    // Добавить возможность авторизации
                        "PATH_TO_AUTHORIZE" => "",    // Страница авторизации
                        "SHOW_REGISTRATION" => "N",    // Добавить возможность регистрации
                        "PATH_TO_REGISTER" => SITE_DIR . "login/",    // Страница регистрации
                        "PATH_TO_PROFILE" => SITE_DIR . "personal/",    // Страница профиля
                        "SHOW_PRODUCTS" => "N",    // Показывать список товаров
                        "POSITION_FIXED" => "N",    // Отображать корзину поверх шаблона
                        "HIDE_ON_BASKET_PAGES" => "Y",    // Не показывать на страницах корзины и оформления заказа
                        "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
                        "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
                    ),
                        false
                    ); ?>
                <? endif ?>
            <? endif ?>
        </div>
    </div>
    <div class="bot">
        <?
        if ($USER->IsAuthorized()) {
            $APPLICATION->IncludeComponent("bitrix:menu", "top", array(
                "COMPONENT_TEMPLATE" => ".default",
                "ROOT_MENU_TYPE" => "top",    // Тип меню для первого уровня
                "MENU_CACHE_TYPE" => "A",    // Тип кеширования
                "MENU_CACHE_TIME" => "36000000000",    // Время кеширования (сек.)
                "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                "MAX_LEVEL" => "1",    // Уровень вложенности меню
                "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
                "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                "DELAY" => "N",    // Откладывать выполнение шаблона меню
                "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
                "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
            ),
                false
            );
        }
        ?>
    </div>
</header>
<main>
    <div id="fullscreenLoader">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M256 0c-8.3 0-15 6.7-15 15v96.4c0 8.3 6.7 15 15 15s15-6.7 15-15V15C271 6.7 264.3 0 256 0z"/>
            <path d="M256 385.6c-8.3 0-15 6.7-15 15V497c0 8.3 6.7 15 15 15s15-6.7 15-15v-96.4C271 392.3 264.3 385.6 256 385.6z"/>
            <path d="M196.7 123.3l-48.2-83.5c-4.1-7.2-13.3-9.6-20.5-5.5 -7.2 4.1-9.6 13.3-5.5 20.5l48.2 83.5c2.8 4.8 7.8 7.5 13 7.5 2.5 0 5.1-0.6 7.5-2C198.4 139.6 200.8 130.4 196.7 123.3z"/>
            <path d="M389.5 457.2l-48.2-83.5c-4.1-7.2-13.3-9.6-20.5-5.5 -7.2 4.1-9.6 13.3-5.5 20.5l48.2 83.5c2.8 4.8 7.8 7.5 13 7.5 2.5 0 5.1-0.6 7.5-2C391.2 473.6 393.6 464.4 389.5 457.2z"/>
            <path d="M138.3 170.7L54.8 122.5c-7.2-4.1-16.3-1.7-20.5 5.5 -4.1 7.2-1.7 16.3 5.5 20.5l83.5 48.2c2.4 1.4 4.9 2 7.5 2 5.2 0 10.2-2.7 13-7.5C147.9 184 145.4 174.9 138.3 170.7z"/>
            <path d="M472.2 363.5l-83.5-48.2c-7.2-4.1-16.3-1.7-20.5 5.5 -4.1 7.2-1.7 16.3 5.5 20.5l83.5 48.2c2.4 1.4 4.9 2 7.5 2 5.2 0 10.2-2.7 13-7.5C481.8 376.8 479.4 367.7 472.2 363.5z"/>
            <path d="M111.4 241H15c-8.3 0-15 6.7-15 15s6.7 15 15 15h96.4c8.3 0 15-6.7 15-15S119.7 241 111.4 241z"/>
            <path d="M497 241h-96.4c-8.3 0-15 6.7-15 15s6.7 15 15 15H497c8.3 0 15-6.7 15-15S505.3 241 497 241z"/>
            <path d="M143.8 320.8c-4.1-7.2-13.3-9.6-20.5-5.5l-83.5 48.2c-7.2 4.1-9.6 13.3-5.5 20.5 2.8 4.8 7.8 7.5 13 7.5 2.5 0 5.1-0.6 7.5-2l83.5-48.2C145.4 337.2 147.9 328 143.8 320.8z"/>
            <path d="M477.7 128c-4.1-7.2-13.3-9.6-20.5-5.5l-83.5 48.2c-7.2 4.1-9.6 13.3-5.5 20.5 2.8 4.8 7.8 7.5 13 7.5 2.5 0 5.1-0.6 7.5-2l83.5-48.2C479.4 144.4 481.8 135.2 477.7 128z"/>
            <path d="M191.2 368.2c-7.2-4.1-16.3-1.7-20.5 5.5l-48.2 83.5c-4.1 7.2-1.7 16.3 5.5 20.5 2.4 1.4 4.9 2 7.5 2 5.2 0 10.2-2.7 13-7.5l48.2-83.5C200.8 381.6 198.4 372.4 191.2 368.2z"/>
            <path d="M384 34.3c-7.2-4.1-16.3-1.7-20.5 5.5l-48.2 83.5c-4.1 7.2-1.7 16.3 5.5 20.5 2.4 1.4 4.9 2 7.5 2 5.2 0 10.2-2.7 13-7.5l48.2-83.5C393.6 47.6 391.2 38.4 384 34.3z"/>
        </svg>
    </div>
    <div class="product-slide-info simplebar-type1" data-simplebar data-simplebar-auto-hide="false">
        <div class="product-slide-content">

        </div>
    </div>