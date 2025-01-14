<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);

global $USER;

use Bitrix\Main\Page\Asset;

global $USER;
$modeAdmin = false;
$session = \Bitrix\Main\Application::getInstance()->getSession();
if ($session->has('mode') && $session['mode'] == '21232f297a57a5a743894a0e4a801fc3' || $USER->isAdmin() || in_array(24, $USER->GetUserGroupArray())) $modeAdmin = true;


?>
<? if ($modeAdmin && CSite::InDir('/catalog/')) : ?>
    <div class="cart-toggler mobile-cart"></div>
<? endif; ?>
</main>
<footer class="<? $APPLICATION->ShowProperty("template_class") ?>">
    <div class="left">
        <div class="item">
            <div class="text-grey2"><?= date('Y'); ?> @ <a class="text-grey2"
                                                           href="<?= file_get_contents(SITE_DIR . "include/footer/email.php") ?>"><? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/email.php", array(), array("MODE" => "text", "NAME" => "email")); ?></a>
            </div>
        </div>
        <div class="item">
            <a href="/rules/" class="text-grey1">Условия продажи</a>
        </div>
        <div class="item">
            <a href="/userconsent/" class="text-grey1">Обработка персональных данных</a>
        </div>
    </div>
    <div class="right">
        <div class="item">
            <p class="text-grey2">Разработано в <a class="text-grey2" href="https://webfly.ru/"
                                                   target="_blank">Вебфлай</a></p>
        </div>
    </div>
</footer>

<div class="mobile-bottom-nav <? $APPLICATION->ShowProperty("template_class") ?>">
    <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom-mobile", array(
        "COMPONENT_TEMPLATE" => ".default",
        "ROOT_MENU_TYPE" => "bottom",    // Тип меню для первого уровня
        "MENU_CACHE_TYPE" => "N",    // Тип кеширования
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
    ); ?>
</div>
<? if ($USER->isAuthorized()): ?>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:menu",
        "vertical_multilevel_custom",
        array(
            "COMPONENT_TEMPLATE" => "vertical_multilevel_custom",
            "ROOT_MENU_TYPE" => "left",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_TIME" => "3600000",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => array(),
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
        ),
        false
    );
    ?>
<? endif; ?>

<?php
if (!CSite::InDir('/personal/order/make/')):?>
    <div class="cart">
        <? $APPLICATION->IncludeComponent(
            "bitrix:sale.basket.basket.line",
            "slide",
            array(
                "HIDE_ON_BASKET_PAGES" => "Y",
                "PATH_TO_BASKET" => SITE_DIR . "personal/cart/",
                "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                "PATH_TO_REGISTER" => SITE_DIR . "login/",
                "POSITION_FIXED" => "Y",
                "POSITION_HORIZONTAL" => "right",
                "POSITION_VERTICAL" => "top",
                "SHOW_AUTHOR" => "N",
                "SHOW_DELAY" => "N",
                "SHOW_EMPTY_VALUES" => "Y",
                "SHOW_IMAGE" => "N",
                "SHOW_NOTAVAIL" => "N",
                "SHOW_NUM_PRODUCTS" => "Y",
                "SHOW_PERSONAL_LINK" => "N",
                "SHOW_PRICE" => "Y",
                "SHOW_PRODUCTS" => "Y",
                "SHOW_SUMMARY" => "Y",
                "SHOW_TOTAL_PRICE" => "Y",
                "COMPONENT_TEMPLATE" => "store_v3",
                "PATH_TO_AUTHORIZE" => "",
                "SHOW_REGISTRATION" => "N",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO"
            ),
            false
        ); ?>
    </div>
<? endif ?>
<?


global $USER;
if ($USER->GetID() == 2389) {
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/admin_assets/script/script.js");
} else {
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/script/script.js");
}
 ?>
<? Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/script.js"); ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        for (var j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(92293757, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true
    });
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/92293757" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

</body>

</html>
