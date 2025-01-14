<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

$APPLICATION->SetTitle("");

?>
<div class="container-size-3">
    <?php
    if (!empty($arResult['ERRORS']['FATAL'])) {
        $component = $this->__component;
        foreach ($arResult['ERRORS']['FATAL'] as $code => $error) {
            if ($code !== $component::E_NOT_AUTHORIZED)
                ShowError($error);
        }

        if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
            ?>
            <? $APPLICATION->AuthForm('', false, false, 'N', false); ?>
            <?
        }
    } else {
        if (!empty($arResult['ERRORS']['NONFATAL'])) {
            foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
                ShowError($error);
            }
        }
        ?>
        <div class="order-detail">
            <? if ($arParams['GUEST_MODE'] !== 'Y') {
                ?>
                <a href="<?= htmlspecialcharsbx($arResult["URL_TO_LIST"]) ?>" class="icon-link mb-6">
                    <div class="icon icon-arrow-left"></div>
                    <span>К списку заказов</span>
                </a>
                <?
            }
            ?>
            <h1> <?= Loc::getMessage('SPOD_LIST_MY_ORDER', array(
                    '#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
                    '#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT_FORMATED"]
                )) ?></h1>

            <h2> Информация о заказе </h2>
            <!-- inprogress ^ canceled ^ done -->
            <?
            if ($arResult['CANCELED'] == 'Y') {
                $oClass = 'canceled';
            } elseif ($arResult["STATUS_ID"] == 'F') {
                $oClass = 'done';
            } else {
                $oClass = 'inprogress';
            }
            ?>
            <div class="order-card <?= $oClass ?>">
                <div class="header">
                    <div>
                        <h4>
                            <? if (is_array($arResult['BASKET'])) {
                                echo count($arResult['BASKET']) . ' ';
                                $count = count($arResult['BASKET']) % 10;
                                if ($count == '1') {
                                    echo Loc::getMessage('SPOD_TPL_GOOD');
                                } elseif ($count >= '2' && $count <= '4') {
                                    echo Loc::getMessage('SPOD_TPL_TWO_GOODS');
                                } else {
                                    echo Loc::getMessage('SPOD_TPL_GOODS');
                                }
                            } ?>
                            <?= Loc::getMessage('SPOD_TPL_SUMOF') ?>
                            <?= $arResult["PRICE_FORMATED"] ?></h4>
                        <div class="subtitle yellow"><?
                            if ($arResult['CANCELED'] !== 'Y') {
                                echo htmlspecialcharsbx($arResult["STATUS"]["NAME"]);
                            } else {
                                echo Loc::getMessage('SPOD_ORDER_CANCELED');
                            }
                            ?>
                        </div>
                    </div>
                    <? if ($arResult['ORDER']['GENERATE_PDF'] && !empty($arResult['ORDER']['PRODUCT_IDS'])) : ?>
                        <div class="download"
                             data-generate-pdf-btn='<?= json_encode(array_values($arResult['ORDER']['PRODUCT_IDS'])) ?>'
                             data-generate-pdf-logo-file='<?= $arResult['ORDER']['LOGO'] ?>'>
                            Маркетинговые файлы <?= $arResult['ORDER']['NAME_COMPANY'] ?>
                        </div>
                    <? endif; ?>
                    <? if ($arParams['GUEST_MODE'] !== 'Y') {
                        ?>
                        <div>
                            <? if ($arResult["CAN_CANCEL"] === "Y") {
                                ?>
                                <a href="<?= $arResult["URL_TO_CANCEL"] ?>" class="link cancel">Отменить</a>
                                <?
                            }
                            ?>
                            <a href="<?= $arResult["URL_TO_COPY"] ?>"
                               class="link">Повторить</a>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
            <h3> Содержимое заказа </h3>
            <div class="product-table-wrapper show">
                <div class="product-table type3 content">
                    <div class="product-table-header">
                        <div class="cell">Наименование</div>
                        <div class="cell">Цена</div>
                        <div class="cell">Количество</div>
                        <div class="cell">Сумма</div>
                    </div>
                    <div class="product-table-body">
                        <? foreach ($arResult['BASKET'] as $basketItem): ?>
                            <div class="product-table-row">
                                <div class="product-table-cell">
                                    <span class="detail-opener-btn"><?= htmlspecialcharsbx($basketItem['NAME']) ?></span>
                                </div>
                                <div class="product-table-cell">
                                    <span class="d-mobile">Цена</span>
                                    <span><?= $basketItem['BASE_PRICE_FORMATED'] ?></span>
                                </div>
                                <div class="product-table-cell">
                                    <span class="d-mobile"> Количество </span>
                                    <span><?= $basketItem['QUANTITY'] ?>&nbsp;
														<?
                                                        if ($basketItem['MEASURE_NAME'] <> '') {
                                                            echo htmlspecialcharsbx($basketItem['MEASURE_NAME']);
                                                        } else {
                                                            echo Loc::getMessage('SPOD_DEFAULT_MEASURE');
                                                        } ?></span>
                                </div>
                                <div class="product-table-cell">
                                    <span class="d-mobile"> Сумма </span>
                                    <span><?= $basketItem['FORMATED_SUM'] ?></span>
                                </div>
                            </div>
                            <div class="detail-info" style="display:none;">
                                <? $detailItemId = $basketItem['PARENT']['ID'] ?: $basketItem['PRODUCT_ID'];
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
                        <? endforeach; ?>
                    </div>
                    <div class="product-table-footer">
                        <span>Итоговая сумма:</span>
                        <div class="price"> <?= $arResult['PRICE_FORMATED'] ?> </div>
                    </div>

                </div>
            </div>
            <? if ($arResult['PAYMENT']): ?>
                <h3> Параметры оплаты </h3>
                <?
                $pCount = 0;
                foreach ($arResult['PAYMENT'] as $pKey => $payment):
                    $pCount++;
                    $paymentData[$payment['ACCOUNT_NUMBER']] = array(
                        "payment" => $payment['ACCOUNT_NUMBER'],
                        "order" => $arResult['ACCOUNT_NUMBER'],
                        "allow_inner" => $arParams['ALLOW_INNER'],
                        "only_inner_full" => $arParams['ONLY_INNER_FULL'],
                        "refresh_prices" => $arParams['REFRESH_PRICES'],
                        "path_to_payment" => $arParams['PATH_TO_PAYMENT']
                    ); ?>
                    <?
                    if ($payment['PAID'] === 'Y') {
                        $pClass = 'green';
                    } elseif ($arResult['IS_ALLOW_PAY'] == 'N') {
                        $pClass = 'red';
                    } else {
                        $pClass = 'red';
                    }
                    if ($pCount < count($arResult['PAYMENT'])) {
                        $pClass .= ' mb-2';
                    }
                    ?>
                    <div class="sale-order-detail-payment-options-methods order-status-card <?= $pClass ?>">
                        <h3>Оплата</h3>
                        <h4><?
                            $paymentSubTitle = Loc::getMessage('SPOD_TPL_BILL') . " " . Loc::getMessage('SPOD_NUM_SIGN') . $payment['ACCOUNT_NUMBER'];
                            if (isset($payment['DATE_BILL'])) {
                                $paymentSubTitle .= " " . Loc::getMessage('SPOD_FROM') . " " . $payment['DATE_BILL_FORMATED'];
                            }
                            $paymentSubTitle .= ",";
                            echo htmlspecialcharsbx($paymentSubTitle);
                            ?> <?= $payment['PAY_SYSTEM_NAME'] ?></h4>
                        <p>
                            <span>Статус: </span>
                            <span class="color subtitle"><?
                                if ($payment['PAID'] === 'Y') {
                                    echo Loc::getMessage('SPOD_PAYMENT_PAID');
                                } elseif ($arResult['IS_ALLOW_PAY'] == 'N' || $arResult['STATUS_ID'] == 'N') {
                                    echo Loc::getMessage('SPOD_TPL_RESTRICTED_PAID');
                                } else {
                                    echo Loc::getMessage('SPOD_PAYMENT_UNPAID');
                                }
                                ?></span>
                        </p>
                        <p>
                            <span><?= Loc::getMessage('SPOD_ORDER_PRICE_BILL') ?>: </span>
                            <span class="color subtitle"><?= $payment['PRICE_FORMATED'] ?></span>
                        </p>
                        <?
                        if ($payment['PAY_SYSTEM']["IS_CASH"] !== "Y" && $payment['PAY_SYSTEM']['ACTION_FILE'] !== 'cash') {
                            if ($arResult['STATUS_ID'] == 'N') {
                                ?>
                                <button class="button-variable disabled"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></button>
                                <?
                            } else {
                                if ($payment['PAY_SYSTEM']['PSA_NEW_WINDOW'] === 'Y' && $arResult["IS_ALLOW_PAY"] !== "N") {
                                    ?>
                                    <a class="button-variable" target="_blank"
                                       href="<?= htmlspecialcharsbx($payment['PAY_SYSTEM']['PSA_ACTION_FILE']) ?>"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></a>
                                    <?
                                } else {
                                    if ($payment["PAID"] === "Y" || $arResult["CANCELED"] === "Y" || $arResult["IS_ALLOW_PAY"] === "N") {
                                        ?>
                                        <button class="button-variable disabled"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></button>
                                        <?
                                    } else {
                                        ?>
                                        <button class="button-variable active-button"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></button>
                                        <?
                                    }
                                }
                            }

                        }
                        ?>
                        <? if ($payment["PAID"] !== "Y"
                            && $payment['PAY_SYSTEM']["IS_CASH"] !== "Y"
                            && $payment['PAY_SYSTEM']['ACTION_FILE'] !== 'cash'
                            && $payment['PAY_SYSTEM']['PSA_NEW_WINDOW'] !== 'Y'
                            && $arResult['CANCELED'] !== 'Y'
                            && $arResult["IS_ALLOW_PAY"] !== "N"
                            && $arResult['STATUS_ID'] !== 'N') {
                            ?>
                            <div class="row sale-order-detail-payment-options-methods-template">
														<span class="sale-paysystem-close active-button">
															<span class="sale-paysystem-close-item sale-order-payment-cancel"></span>
                                                            <!--sale-paysystem-close-item-->
														</span><!--sale-paysystem-close-->
                                <div class="col">
                                    <?= $payment['BUFFERED_OUTPUT'] ?>
                                    <!--<a class="sale-order-payment-cancel">-->
                                    <?//= Loc::getMessage('SPOD_CANCEL_PAY')
                                    ?><!--</a>-->
                                </div>
                            </div>
                            <?
                        }
                        ?>
                    </div>
                <? endforeach ?>
            <? endif ?>
            <? if (count($arResult['SHIPMENT'])): ?>
                <h3>Параметры отгрузки</h3>
                <? foreach ($arResult['SHIPMENT'] as $shipment): ?>
                    <div class="order-status-card <?= $shipment['DEDUCTED'] === 'Y' ? 'green' : 'yellow' ?>">
                        <h3>Доставка</h3>
                        <h4>    <?
                            //change date
                            if ($shipment['PRICE_DELIVERY'] == '') {
                                $shipment['PRICE_DELIVERY'] = 0;
                            }
                            $shipment['PRICE_DELIVERY_FORMATED'] = \SaleFormatCurrency(
                                    $shipment['PRICE_DELIVERY'],
                                    'RUB',
                                    true
                                ) . ' ₽';
                            $shipmentRow = Loc::getMessage('SPOD_SUB_ORDER_SHIPMENT') . " " . Loc::getMessage('SPOD_NUM_SIGN') . $shipment["ACCOUNT_NUMBER"];
                            if ($shipment["DATE_DEDUCTED"]) {
                                $shipmentRow .= " " . Loc::getMessage('SPOD_FROM') . " " . $shipment["DATE_DEDUCTED_FORMATED"];
                            }
                            $shipmentRow = htmlspecialcharsbx($shipmentRow);
                            $shipmentRow .= ", " . Loc::getMessage('SPOD_SUB_PRICE_DELIVERY', array(
                                    '#PRICE_DELIVERY#' => $shipment['PRICE_DELIVERY_FORMATED']
                                ));
                            echo $shipmentRow;
                            ?></h4>
                        <p>
                            <span>Статус отгрузки: </span>
                            <span class="color subtitle"><?= htmlspecialcharsbx($shipment['STATUS_NAME']) ?></span>
                        </p>
                        <p>
                            <span>Отгрузка №<?= htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']) ?>: </span>
                            <span class="subtitle"><?
                                if ($shipment['DEDUCTED'] == 'Y') {
                                    echo 'Отгружено';
                                } else {
                                    echo 'Не отгружено';
                                } ?></span>
                        </p>
                        <? if ($shipment["DELIVERY_NAME"] <> '') {
                            ?>
                            <p>
                                <span>Служба доставки: </span>
                                <span class="subtitle"><?= htmlspecialcharsbx($shipment["DELIVERY_NAME"]) ?></span>
                            </p>
                            <?
                        }
                        ?>
                    </div>
                <? endforeach ?>
            <? endif ?>
        </div>
    <?
    $javascriptParams = array(
        "url" => CUtil::JSEscape($this->__component->GetPath() . '/ajax.php'),
        "templateFolder" => CUtil::JSEscape($templateFolder),
        "templateName" => $this->__component->GetTemplateName(),
        "paymentList" => $paymentData,
        "returnUrl" => $arResult['RETURN_URL'],
    );
    $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
    ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                BX.Sale.PersonalOrderComponent.PersonalOrderDetail.init(<?=$javascriptParams?>);
                initDetailOpener('.product-table-row');
            });
        </script>
        <?
    }
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