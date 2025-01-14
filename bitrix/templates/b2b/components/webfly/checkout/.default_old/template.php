<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
Extension::load("ui.vue");
$component = $this->__component;
$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$this->addExternalJs($templateFolder . '/js/location.js');
$this->addExternalJs($templateFolder . '/js/validation.js');
$documentRoot = Main\Application::getDocumentRoot();
$vTemplates = new Main\IO\Directory($documentRoot . $templateFolder . '/v-templates');
if ($vTemplates) {
    foreach ($vTemplates->getChildren() as $vTemplate) {
        include($vTemplate->getPath());
    }
}
if (strlen($request->get('ORDER_ID')) > 0) {//заказ сформирован
    include(Main\Application::getDocumentRoot() . $templateFolder . '/confirm.php');
} elseif ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET']) {//пустая корзина
    include(Main\Application::getDocumentRoot() . $templateFolder . '/empty.php');
} else {
    ?>
    <div class="checkout-page">
        <form action="<?= POST_FORM_ACTION_URI ?>" method="POST" name="ORDER_FORM" id="checkout-form"
              enctype="multipart/form-data">
            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="<?= $arParams['ACTION_VARIABLE'] ?>" value="saveOrderAjax">
            <input type="hidden" name="location_type" value="code">
            <input type="checkbox" name="licenses_order" checked="" required="" value="Y" style="display:none;">
            <input id="ID_PAY_SYSTEM_ID_14" name="PAY_SYSTEM_ID" type="checkbox" value="14" checked
                   style="display:none;">
            <div id="checkout-app"></div>
        </form>
    </div>
    <template id="checkout">
        <div class="checkout-container">
            <div class="left"></div>
            <div class="mid">
                <h1><? $APPLICATION->showTitle(false); ?></h1>
                <h2>Товары в заказе</h2>
                <template>
                    <checkout-products :products="products" :total="total" :rows="rows"/>
                </template>
                <h3>Выберите компанию, на которую оформлять документы</h3>
                <template>
                    <checkout-region-block :regiondata="regiondata" :properties="properties"
                                           :locations="locations"/>
                </template>
                <template>
                    <checkout-properties :grouped_properties="grouped_properties" :user_data="user_data" :contact_person="contact_person"/>
                </template>
                <h3 class="group-title">Доставка</h3>
                <template>
                    <checkout-delivery-properties :grouped_properties="grouped_properties"/>
                </template>
                <template>
                    <checkout-delivery :delivery="delivery" :regiondata="regiondata"/>
                </template>
                <template>
                    <checkout-delivery-prop :grouped_properties="grouped_properties"/>
                </template>
                <div class="container-size-2 ml-0 p-0">
                    <div class="custom-form">
                        <div class="form-group"></div>
                        <div class="form-group">
                            <div class="form-col">
                                <template>
                                    <checkout-alert :total="total"/>
                                </template>
                            </div>
                            <div class="form-col"></div>
                        </div>
                    </div>
                </div>
                <div class="wf-popup popup_map disable" id="popup_map">
                    <div class="popup_map--map">
                        <div style="position:relative;overflow:hidden;">
                            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A10c60164bcd3b43bea49bf20863ce4249577839d0fddb159a318735417e365b2&amp;source=constructor"
                                    width="830" height="650" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="popup_map--close" @click="closePopup">
                        <div class="icon icon-cross"></div>
                    </div>
                </div>
                <div class="wf-popup popup_map disable" id="popup_map-retail">
                    <div class="popup_map--map">
                        <div style="position:relative;overflow:hidden;">
                            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A64f0e3aff593840fc0ed2966e03356ab5e996f7a5b34ac973005b487c4cf2d65&amp;source=constructor"
                                    width="830" height="650" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="popup_map--close" @click="closePopup">
                        <div class="icon icon-cross"></div>
                    </div>
                </div>
            </div>
            <div class="right show">
                <template>
                    <checkout-basket :basket="basket" :total="total"/>
                </template>
            </div>
        </div>
    </template>
    <div class="checkout-warn" id="popupStock" style="display: none ; width: 320px; border: 2px solid #f2f2f2;">
        <span>Товар со статусом «под заказ» <br>Срок поставки до <span class="day"></span> дней</span>
        <button class="button-full mt-2">Закрыть</button>
    </div>
    <?
    // spike: for children of cities we place this prompt
    $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
    $bxLocationParams = array(
        'source' => $component->getPath() . '/get.php',
        'cityTypeId' => intval($city['ID']),
        'messages' => array(
            'otherLocation' => '--- ' . Loc::getMessage('SOA_OTHER_LOCATION'),
            'moreInfoLocation' => '--- ' . Loc::getMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
            'notFoundPrompt' => '<div class="-bx-popup-special-prompt">' . Loc::getMessage('SOA_LOCATION_NOT_FOUND') . '.<br />' . Loc::getMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                    '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                    '#ANCHOR_END#' => '</a>'
                )) . '</div>'
        )
    );
    $signer = new Main\Security\Sign\Signer;
    $signedTemplate = $signer->sign($templateName, 'webfly.checkout');
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'webfly.checkout');
    $messages = Loc::loadLanguageFile(__FILE__); ?>
    <script>
        window.addEventListener('b24:form:init', (event) => {
            let form = event.detail.object;
            if (form.identification.id == 28) {
                form.setProperty("my_param1", "Задвоение ИНН у клиента");
            }
        });
    </script>
    <script data-b24-form="click/28/cmqscl" data-skip-moving="true">(function (w, d, u) {
            var s = d.createElement('script');
            s.async = true;
            s.src = u + '?' + (Date.now() / 180000 | 0);
            var h = d.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s, h);
        })(window, document, 'https://crm.legend-tea.ru/upload/crm/form/loader_28_cmqscl.js');</script>
    <button id="BX_FORM" style="display: none">задать вопрос</button>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            BX.WebflyCheckoutComponent.init({
                basketUrl: '<?=CUtil::JSEscape(SITE_TEMPLATE_PATH . '/ajax/basket.php')?>',
                sessidMsg: '<?=Loc::getMessage('SESSID_ERROR')?>',
                result: <?=CUtil::PhpToJSObject($arResult["JS_DATA"], false, false, true)?>,
                locations: <?=CUtil::PhpToJSObject($arResult['LOCATIONS'])?>,
                params: <?=CUtil::PhpToJSObject($arParams)?>,
                siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
                ajaxUrl: '<?=CUtil::JSEscape($component->getPath() . '/ajax.php')?>',
                templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
                signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
                template: '<?=CUtil::JSEscape($signedTemplate)?>',
                BXLocationParams: <?=CUtil::PhpToJSObject($bxLocationParams)?>
            });
        });
    </script>
    <?
}
?>



