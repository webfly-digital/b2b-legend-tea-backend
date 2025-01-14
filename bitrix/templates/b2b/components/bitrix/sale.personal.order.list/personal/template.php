<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
?>
<div class="mid">
    <?php
    $component = $this->__component;
    if (!empty($arResult['ERRORS']['FATAL'])):
        foreach ($arResult['ERRORS']['FATAL'] as $code => $error) {
            if ($code !== $component::E_NOT_AUTHORIZED)
                ShowError($error);
        }
    else:
        if (!empty($arResult['ERRORS']['NONFATAL'])) {
            foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
                ShowError($error);
            }
        }
        if (!count($arResult['ORDERS'])) {
            if ($_REQUEST["filter_history"] == 'Y') {
                if ($_REQUEST["show_canceled"] == 'Y') {
                    ?>
                    <div class="note-plate"><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER') ?></div>
                    <?
                } else {
                    ?>
                    <div class="note-plate"><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST') ?></div>
                    <?
                }
            } else {
                ?>
                <div class="note-plate"><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST') ?></div>
                <?
            }
        }
        ?>
        <div class="order-group">
            <?
            if ($_REQUEST["filter_history"] !== 'Y') {//текущие
                $paymentChangeData = array();
                $orderHeaderStatus = null;
                foreach ($arResult['ORDERS'] as $key => $order) {
                    $orderHeaderStatus = $order['ORDER']['STATUS_ID'];
                    if ($order['PAYMENT']) $currentPayment = current($order['PAYMENT']);
                    ?>
                    <div class="order-card inprogress">
                        <div class="header">
                            <div>
                                <h4>
                                    <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>"><?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                                        <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . $order['ORDER']['ACCOUNT_NUMBER'] ?>
                                        <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                                        <?= $order['ORDER']['DATE_INSERT_FORMATED'] ?>,
                                        <? if (is_array($order['BASKET_ITEMS'])) {
                                            echo count($order['BASKET_ITEMS']) . ' ';
                                            $count = count($order['BASKET_ITEMS']) % 10;
                                            if ($count == '1') {
                                                echo Loc::getMessage('SPOL_TPL_GOOD');
                                            } elseif ($count >= '2' && $count <= '4') {
                                                echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                                            } else {
                                                echo Loc::getMessage('SPOL_TPL_GOODS');
                                            }
                                        } ?>
                                        <?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
                                        <?= $order['ORDER']['PRICE_FORMATED'] ?></a></h4>
                                <div class="subtitle <?= $currentPayment['PAID'] === 'Y' ? 'green' : 'yellow' ?>"><?= htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME']) ?></div>
                                <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>" class="link">Информация
                                    о заказе</a>
                            </div>
                            <div>
                                <? if ($order['ORDER']['GENERATE_PDF'] && !empty($order['ORDER']['PRODUCT_IDS'])) : ?>
                                    <div class="download"
                                         data-generate-pdf-btn='<?= json_encode(array_values($order['ORDER']['PRODUCT_IDS'])) ?>'
                                         data-generate-pdf-logo-file='<?= $order['ORDER']['LOGO'] ?>'
                                    >
                                        Маркетинговые файлы
                                    </div>
                                <? endif; ?>
                                <?
                                if ($order['ORDER']['CAN_CANCEL'] !== 'N') {
                                    ?>
                                    <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"]) ?>"
                                       class="link cancel">Отменить</a>
                                    <?
                                }
                                ?>
                                <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>" class="link">Повторить</a>
                            </div>
                        </div>
                        <div class="body">
                            <div class="status-cards">
                                <? if ($order['PAYMENT']): ?>
                                    <div class="order-status-card <?= $currentPayment['PAID'] === 'Y' ? 'green' : 'red' ?>">
                                        <h3>Оплата</h3>
                                        <? foreach ($order['PAYMENT'] as $payment):
                                            if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y') {
                                                $paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
                                                    "order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
                                                    "payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
                                                    "allow_inner" => $arParams['ALLOW_INNER'],
                                                    "refresh_prices" => $arParams['REFRESH_PRICES'],
                                                    "path_to_payment" => $arParams['PATH_TO_PAYMENT'],
                                                    "only_inner_full" => $arParams['ONLY_INNER_FULL'],
                                                    "return_url" => $arResult['RETURN_URL'],
                                                );
                                            } ?>
                                            <h4><?
                                                $paymentSubTitle = Loc::getMessage('SPOL_TPL_BILL') . " " . Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . htmlspecialcharsbx($payment['ACCOUNT_NUMBER']);
                                                if (isset($payment['DATE_BILL'])) {
                                                    $paymentSubTitle .= " " . Loc::getMessage('SPOL_TPL_FROM_DATE') . " " . $payment['DATE_BILL_FORMATED'];
                                                }
                                                $paymentSubTitle .= ",";
                                                echo $paymentSubTitle; ?> <?= $payment['PAY_SYSTEM_NAME'] ?>
                                            </h4>
                                            <p>
                                                <span>Статус: </span>
                                                <span class="color subtitle">
                                                    <? if ($payment['PAID'] === 'Y') {
                                                        ?>
                                                        <?= Loc::getMessage('SPOL_TPL_PAID') ?>
                                                        <?
                                                    } elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N' || $order['ORDER']['STATUS_ID'] == 'N') {
                                                        ?>
                                                        <?= Loc::getMessage('SPOL_TPL_RESTRICTED_PAID') ?>
                                                        <?
                                                    } else {
                                                        ?>
                                                        <?= Loc::getMessage('SPOL_TPL_NOTPAID') ?>
                                                        <?
                                                    }
                                                    ?>
                                                </span>
                                            </p>
                                            <p>
                                                <span><?= Loc::getMessage('SPOL_TPL_SUM_TO_PAID') ?>: </span>
                                                <span class="color subtitle"><?= $payment['FORMATED_SUM'] ?></span>
                                            </p>

                                            <?
                                            if ($payment['PAID'] === 'N' && $payment['IS_CASH'] !== 'Y' && $payment['ACTION_FILE'] !== 'cash') {
                                                if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' || $order['ORDER']['STATUS_ID'] == 'N') {
                                                    ?>
                                                    <button class="button-variable disabled"><?= Loc::getMessage('SPOL_TPL_PAY') ?></button>
                                                    <?
                                                } elseif ($payment['NEW_WINDOW'] === 'Y') {
                                                    ?>
                                                    <a class="button-variable" target="_blank"
                                                       href="<?= htmlspecialcharsbx($payment['PSA_ACTION_FILE']) ?>"><?= Loc::getMessage('SPOL_TPL_PAY') ?></a>
                                                    <?
                                                } else {
                                                    ?>
                                                    <a class="button-variable ajax_reload" target="_blank"
                                                       href="<?= htmlspecialcharsbx($payment['PSA_ACTION_FILE']) ?>"><?= Loc::getMessage('SPOL_TPL_PAY') ?></a>
                                                    <?
                                                }
                                            }
                                            ?>
                                        <? endforeach ?>
                                    </div>
                                <? endif ?>
                                <? if ($order['SHIPMENT']):
                                    $currentShipment = [];
                                    foreach ($order['SHIPMENT'] as $shipment) {
                                        if (empty($shipment)) continue;
                                        $currentShipment = $shipment;
                                        break;
                                    } ?>
                                    <div class="order-status-card <?= $currentShipment['DEDUCTED'] === 'Y' ? 'green' : 'yellow' ?>">
                                        <h3>Доставка</h3>
                                        <? foreach ($order['SHIPMENT'] as $shipment):
                                            if (empty($shipment)) continue; ?>

                                        <? endforeach ?>
                                        <h4><?= Loc::getMessage('SPOL_TPL_LOAD') ?>
                                            <?
                                            $shipmentSubTitle = Loc::getMessage('SPOL_TPL_NUMBER_SIGN') . htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']);
                                            if ($shipment['DATE_DEDUCTED']) {
                                                $shipmentSubTitle .= " " . Loc::getMessage('SPOL_TPL_FROM_DATE') . " " . $shipment['DATE_DEDUCTED_FORMATED'];
                                            }

                                            if ($shipment['FORMATED_DELIVERY_PRICE']) {
                                                $shipmentSubTitle .= ", " . Loc::getMessage('SPOL_TPL_DELIVERY_COST') . " " . $shipment['FORMATED_DELIVERY_PRICE'];
                                            }
                                            echo $shipmentSubTitle;
                                            ?></h4>
                                        <p>
                                            <span>Статус отгрузки: </span>
                                            <span class="color subtitle"><?= htmlspecialcharsbx($shipment['DELIVERY_STATUS_NAME']) ?></span>
                                        </p>
                                        <p>
                                            <span>Отгрузка №<?= htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']) ?>: </span>
                                            <span class="subtitle">
                                                <?
                                                if ($shipment['DEDUCTED'] == 'Y') {
                                                    ?>
                                                    <?= Loc::getMessage('SPOL_TPL_LOADED'); ?>
                                                    <?
                                                } else {
                                                    ?>
                                                    <?= Loc::getMessage('SPOL_TPL_NOTLOADED'); ?>
                                                    <?
                                                } ?>
                                            </span>
                                        </p>
                                        <?php
                                        if (!empty($shipment['DELIVERY_ID'])) {
                                            ?>
                                            <p>
                                                <span>Служба доставки: </span>
                                                <span class="subtitle"><?= $arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME'] ?></span>
                                            </p>
                                            <?
                                        }
                                        ?>
                                    </div>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                    <?
                }
            } else {
                //выполненные и отмененные
                $orderHeaderStatus = null;
                foreach ($arResult['ORDERS'] as $key => $order) {
                    ?>
                    <div class="order-card <?= $_REQUEST["show_canceled"] === 'Y' ? 'canceled' : 'done' ?>">
                        <div class="header">
                            <div>
                                <h4>
                                    <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>"><?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                                        <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
                                        <?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>
                                        <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                                        <span class="text-nowrap"><?= $order['ORDER']['DATE_INSERT_FORMATED'] ?>,</span>
                                        <?= count($order['BASKET_ITEMS']); ?>
                                        <?
                                        $count = mb_substr(count($order['BASKET_ITEMS']), -1);
                                        if ($count == '1') {
                                            echo Loc::getMessage('SPOL_TPL_GOOD');
                                        } elseif ($count >= '2' || $count <= '4') {
                                            echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                                        } else {
                                            echo Loc::getMessage('SPOL_TPL_GOODS');
                                        }
                                        ?>
                                        <?= Loc::getMessage('SPOL_TPL_SUMOF') ?> <?= $order['ORDER']['PRICE_FORMATED'] ?>
                                    </a></h4>
                                <div class="subtitle">
                                    Заказ <?= $_REQUEST["show_canceled"] === 'Y' ? 'отменен' : 'выполнен' ?></div>
                                <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>" class="link">Информация
                                    о заказе</a>
                            </div>
                            <div>
                                <? if ($order['ORDER']['GENERATE_PDF'] && !empty($order['ORDER']['PRODUCT_IDS'])) : ?>
                                    <div class="download"
                                         data-generate-pdf-btn='<?= json_encode(array_values($order['ORDER']['PRODUCT_IDS'])) ?>'
                                         data-generate-pdf-logo-file='<?= $order['ORDER']['LOGO'] ?>'>
                                        Маркетинговые файлы
                                    </div>
                                <? endif; ?>
                                <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>" class="link">Повторить</a>
                            </div>
                        </div>
                    </div>
                    <?
                }
            }
            ?>
        </div>
        <?
        if ($arResult["NAV_STRING"]) {
            echo '<br>';
            echo $arResult["NAV_STRING"];
        }
    endif;
    ?>
</div>
<script>
    function generatePdf() {
        var target = BX.proxy_context;
        var data = {};
        if (target.hasAttribute('data-generate-pdf-btn')) {
            let arIds = JSON.parse(target.getAttribute('data-generate-pdf-btn'));
            data = {formData: {'ID_ELEM': arIds}};


            if (target.hasAttribute('data-generate-pdf-logo-file')) {
                let logo = target.getAttribute('data-generate-pdf-logo-file');
                if (logo.length > 0) {
                    data.formData.LOGO = logo;
                }
            }

            BX.ajax.runComponentAction('webfly:pdf.product', 'generatePdf', {
                mode: 'class',
                data: data,
            }).then(async function (response) {
                if (response.data.hasOwnProperty('product')) {
                    var ob = BX.processHTML(response.data.product);
                    document.body.insertAdjacentHTML('beforeend', ob.HTML);
                    BX.ajax.processScripts(ob.SCRIPT);
                }
            }, function () {
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        let downloadPdfBtn = document.querySelectorAll('[data-generate-pdf-btn]');
        if (downloadPdfBtn) {
            downloadPdfBtn.forEach(item => {
                item.addEventListener('click', BX.delegate(this.generatePdf, this));
            });
        }
    });

</script>