<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

/** @global CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;

CJSCore::init(array('popup'));

$randomString = $this->randString();

$APPLICATION->setTitle(Loc::getMessage('CPSL_SUBSCRIBE_TITLE_NEW'));
if (!$arResult['USER_ID'] && !isset($arParams['GUEST_ACCESS'])):?>
    <? $APPLICATION->authForm('', false, false, 'N', false); ?>
    <? $APPLICATION->setTitle(Loc::getMessage('CPSL_TITLE_PAGE_WHEN_ACCESSING')); ?>
<?else:?>
<script type="text/javascript">
    BX.message({
        CPSL_MESS_BTN_DETAIL: '<?=('' != $arParams['MESS_BTN_DETAIL']
            ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',

        CPSL_MESS_NOT_AVAILABLE: '<?=('' != $arParams['MESS_BTN_DETAIL']
            ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',
        CPSL_BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_BASKET_REDIRECT');?>',
        CPSL_BASKET_URL: '<?=$arParams["BASKET_URL"];?>',
        CPSL_TITLE_ERROR: '<?=GetMessageJS('CPSL_CATALOG_TITLE_ERROR') ?>',
        CPSL_TITLE_BASKET_PROPS: '<?=GetMessageJS('CPSL_CATALOG_TITLE_BASKET_PROPS') ?>',
        CPSL_BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CPSL_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
        CPSL_BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_SEND_PROPS');?>',
        CPSL_BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_CLOSE') ?>',
        CPSL_STATUS_SUCCESS: '<?=GetMessageJS('CPSL_STATUS_SUCCESS');?>',
        CPSL_STATUS_ERROR: '<?=GetMessageJS('CPSL_STATUS_ERROR') ?>'
    });
</script>
<?

if (!empty($_GET['result']) && !empty($_GET['message'])) {
    $successNotify = mb_strpos($_GET['result'], 'Ok') ? true : false;
    $postfix = $successNotify ? 'Ok' : 'Fail';
    $popupTitle = Loc::getMessage('CPSL_SUBSCRIBE_POPUP_TITLE_' . mb_strtoupper(str_replace($postfix, '', $_GET['result'])));

    $arJSParams = array(
        'NOTIFY_USER' => true,
        'NOTIFY_POPUP_TITLE' => $popupTitle,
        'NOTIFY_SUCCESS' => $successNotify,
        'NOTIFY_MESSAGE' => urldecode($_GET['message']),
    );
    ?>
    <script type="text/javascript">
        var <?='jaClass_' . $randomString;?> = new JCCatalogProductSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
    </script>
    <?
}
?>
<div class="container-size-3">
    <div class="notification-page">
        <h1><? $APPLICATION->showTitle(); ?></h1>
        <div class="note-plate">Тут отображается отсутствующие позиции из каталога, на которые вы подписались для
            информирования о поступлении товаров на склад. Как только товар появится, вам отправится письмо о
            поступлении товара на склад.
        </div>
        <?php
        if (!empty($arResult['ITEMS'])):
            ?>
            <div class="notification-table">
                <div class="notification-table-header">
                    <div class="cell">Наименование</div>
                    <div class="cell">Действие</div>
                </div>
                <div class="notification-table-body">
                    <?php foreach ($arResult['ITEMS'] as $key => $arItem):
                        $strMainID = $this->GetEditAreaId($arItem['ID']);
                        $arItemIDs = array(
                            'ID' => $strMainID,
                            'PICT' => $strMainID . '_pict',
                            'SECOND_PICT' => $strMainID . '_secondpict',
                            'MAIN_PROPS' => $strMainID . '_main_props',

                            'QUANTITY' => $strMainID . '_quantity',
                            'QUANTITY_DOWN' => $strMainID . '_quant_down',
                            'QUANTITY_UP' => $strMainID . '_quant_up',
                            'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                            'BUY_LINK' => $strMainID . '_buy_link',
                            'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
                            'SUBSCRIBE_DELETE_LINK' => $strMainID . '_delete_subscribe',

                            'PRICE' => $strMainID . '_price',
                            'DSC_PERC' => $strMainID . '_dsc_perc',
                            'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

                            'PROP_DIV' => $strMainID . '_sku_tree',
                            'PROP' => $strMainID . '_prop_',
                            'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                            'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
                        );
                        $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);?>
                        <div class="notification-table-row" id="<?= $strMainID; ?>">
                            <div class="notification-table-cell">
                                <span class="detail-opener-btn"><?= $arItem['NAME']; ?></span>
                            </div>
                            <div class="notification-table-cell">
                                <a id="<?= $arItemIDs['SUBSCRIBE_DELETE_LINK']; ?>" href="javascript:void(0)" class="link">Отменить подписку</a>
                            </div>
                        </div>
                        <div class="detail-info" style="display:none;">
                            <? $detailItemId =  $arItem['ID'];
                            $detailItem = $arResult['DETAIL_INFO'][$detailItemId]; ?>
                            <div class="header">
                                <div class="icon icon-cross close"></div>
                            </div>
                            <div class="body">
                                <div class="slider">
                                    <? if ($detailItem['PICTURES']): ?>
                                        <div class="splide">
                                            <div class="splide__track">
                                                <ul class="splide__list">
                                                    <? foreach ($detailItem['PICTURES'] as $picture): ?>
                                                        <li class="splide__slide">
                                                            <div class="slide">
                                                                <div class="content">
                                                                    <img src="<?= $picture['src'] ?>"
                                                                         alt="<?= $detailItem['FIELDS']['NAME'] ?>"
                                                                         title="<?= $detailItem['FIELDS']['NAME'] ?>">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <? endforeach ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <? endif ?>
                                </div>
                                <div class="subslider">
                                    <div class="labels">
                                        <? if ($detailItem['PROPERTIES']['PROPERTY_CML2_ARTICLE']): ?>
                                            <div class="label grey-noborder"><?= $detailItem['PROPERTIES']['PROPERTY_CML2_ARTICLE'] ?></div>
                                        <? endif ?>
                                        <? if ($detailItem['LABEL']['ICON']): ?>
                                            <div class="label <?= $detailItem['LABEL']['CLASS'] ?>">
                                                <div class="icon icon-<?= $detailItem['LABEL']['ICON'] ?>"></div>
                                                <span><?= $detailItem['LABEL']['TEXT'] ?></span>
                                            </div>
                                        <? endif ?>
                                        <div class="label grey" <?= ($basketItem['CAN_BUY'] ? 'style="display: none;"' : '') ?>>
                                            <div class="icon icon-truck"></div>
                                            <span>Нет в наличии</span>
                                        </div>
                                    </div>
                                </div>
                                <h3><?= $detailItem['FIELDS']['NAME'] ?></h3>
                                <div class="subtitle"><?= $detailItem['FIELDS']['PREVIEW_TEXT'] ?></div>
                                <div class="characteristics">
                                    <? if (!empty($detailItem['DISPLAY_PROPERTIES'])): ?>
                                        <h4 class="title">Характеристики:</h4>
                                        <div class="text-items">
                                            <? foreach ($detailItem['DISPLAY_PROPERTIES'] as $displayProperty): ?>
                                                <div class="item">
                                                    <div class="name"><?= $displayProperty['NAME'] ?>:</div>
                                                    <div class="value"><?= (is_array($displayProperty['DISPLAY_VALUE'])
                                                            ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                                            : $displayProperty['DISPLAY_VALUE']) ?></div>
                                                </div>
                                            <? endforeach ?>
                                        </div>
                                    <? endif ?>
                                    <? if ($detailItem['RANGES']): ?>
                                        <div class="progress-items">
                                            <? foreach ($detailItem['RANGES'] as $rangeItem): ?>
                                                <div class="item">
                                                    <div class="progress"
                                                         style="--val: <?= $rangeItem['VALUE'] ?>%"></div>
                                                    <div class="text"><?= $rangeItem['NAME'] ?></div>
                                                </div>
                                            <? endforeach ?>
                                        </div>
                                    <? endif ?>
                                </div>
                                <? if ($detailItem['PROPERTIES']['PROPERTY_OPISANIE_DLYA_SAYTA'] || $detailItem['FIELDS']['DETAIL_TEXT']): ?>
                                    <div class="description">
                                        <div class=" text-content">
                                            <h4 class="title">Описание:</h4>
                                            <?= $detailItem['PROPERTIES']['PROPERTY_OPISANIE_DLYA_SAYTA'] ?: $detailItem['FIELDS']['DETAIL_TEXT'] ?>
                                        </div>
                                    </div>
                                <? endif ?>
                            </div>
                        </div>
                    <?php
                        $arJSParams = array(
                            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                            'SHOW_ADD_BASKET_BTN' => false,
                            'SHOW_BUY_BTN' => true,
                            'SHOW_ABSENT' => true,
                            'PRODUCT' => array(
                                'ID' => $arItem['ID'],
                                'NAME' => $arItem['~NAME'],
                                'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
                                'CAN_BUY' => $arItem["CAN_BUY"],
                                'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                                'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                                'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                                'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                                'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                                'ADD_URL' => $arItem['~ADD_URL'],
                                'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
                                'LIST_SUBSCRIBE_ID' => $arParams['LIST_SUBSCRIPTIONS'],
                            ),
                            'BASKET' => array(
                                'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
                                'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                                'EMPTY_PROPS' => $emptyProductProperties
                            ),
                            'VISUAL' => array(
                                'ID' => $arItemIDs['ID'],
                                'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
                                'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                                'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                                'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                                'PRICE_ID' => $arItemIDs['PRICE'],
                                'BUY_ID' => $arItemIDs['BUY_LINK'],
                                'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
                                'DELETE_SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_DELETE_LINK'],
                            ),
                            'LAST_ELEMENT' => $arItem['LAST_ELEMENT'],
                        );?>
                        <script type="text/javascript">
                            var <?=$strObName;?> = new JCCatalogProductSubscribeList(
                                <?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
                        </script>
                    <?
                    endforeach; ?>
                </div>
            </div>
        <?
        else:
            if (isset($arParams['GUEST_ACCESS'])):
                echo '<h4>' . Loc::getMessage('CPSL_SUBSCRIBE_NOT_FOUND') . '</h4>';
            endif;
        endif;
        ?>
    </div>
</div>
<? endif; ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        initDetailOpener('.notification-table-row');
    });
</script>
