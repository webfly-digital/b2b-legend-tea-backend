<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
?>
<?php
$component = $this->__component;

if (empty($arResult['ERRORS']['FATAL']) && $arResult['ORDERS']):
    ?>
    <section>
        <div class="section-title">
            <h2>Активные заказы</h2>
            <a href="/personal/orders/" class="icon-link">
                <span>Все заказы</span>
                <div class="icon icon-arrow-right"></div>
            </a>

        </div>
        <div class="order-plate-group">
            <?
            $paymentChangeData = array();
            $orderHeaderStatus = null;

            foreach ($arResult['ORDERS'] as $key => $order) {
                $orderHeaderStatus = $order['ORDER']['STATUS_ID'];
                if ($order['PAYMENT']) $currentPayment = current($order['PAYMENT']);
                ?>
                <?
                $paymentStatus = [
                    'MESSAGE' => '',
                    'CLASS' => ''
                ];
                if ($currentPayment['PAID'] === 'Y') {
                    $paymentStatus = [
                        'MESSAGE' => Loc::getMessage('SPOL_TPL_PAID'),
                        'CLASS' => 'green'
                    ];
                } elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N' || $order['ORDER']['STATUS_ID'] == 'N') {
                    $paymentStatus = [
                        'MESSAGE' => Loc::getMessage('SPOL_TPL_RESTRICTED_PAID'),
                        'CLASS' => 'neutral'
                    ];
                } else {
                    $paymentStatus = [
                        'MESSAGE' => Loc::getMessage('SPOL_TPL_NOTPAID'),
                        'CLASS' => 'red'
                    ];
                }
                ?>
                <div class="order-plate <?= $paymentStatus['CLASS']?>">
                    <div class="left">
                        <a href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"]) ?>">
                            <h4><?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                                <?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
                                <?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>
                                <?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
                                <span class="text-nowrap"><?= $order['ORDER']['DATE_INSERT_FORMATED'] ?>,</span>
                                <?if (is_array($order['BASKET_ITEMS'])){
                                    echo count($order['BASKET_ITEMS']).' ';
                                    $count = mb_substr(count($order['BASKET_ITEMS']), -1);
                                    if ($count == '1') {
                                        echo Loc::getMessage('SPOL_TPL_GOOD');
                                    } elseif ($count >= '2' || $count <= '4') {
                                        echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
                                    } else {
                                        echo Loc::getMessage('SPOL_TPL_GOODS');
                                    }
                                }?>
                               <?= Loc::getMessage('SPOL_TPL_SUMOF') ?> <?= $order['ORDER']['PRICE_FORMATED'] ?>
                            </h4>
                        </a>
                        <div><?= $paymentStatus['MESSAGE']?>, <?= htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME']) ?></div>
                    </div>
                    <div class="right">
                        <?
                        if ($currentPayment['PAID'] === 'N' && $currentPayment['IS_CASH'] !== 'Y' && $currentPayment['ACTION_FILE'] !== 'cash') {
                            if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' || $order['ORDER']['STATUS_ID'] == 'N') {
                                ?>
                                <a class="button-variable disabled"
                                   href="#"><?= Loc::getMessage('SPOL_TPL_PAY') ?></a>
                                <?
                            } elseif ($currentPayment['NEW_WINDOW'] === 'Y') {
                                ?>
                                <a class="button-variable" target="_blank"
                                   href="<?= htmlspecialcharsbx($currentPayment['PSA_ACTION_FILE']) ?>"><?= Loc::getMessage('SPOL_TPL_PAY') ?></a>
                                <?
                            } else {
                                ?>
                                <a class="button-variable ajax_reload" target="_blank"
                                   href="<?= htmlspecialcharsbx($currentPayment['PSA_ACTION_FILE']) ?>"><?= Loc::getMessage('SPOL_TPL_PAY') ?></a>
                                <?
                            }
                        }
                        ?>
                    </div>
                </div>
                <?
            }
            ?>
        </div>
    </section>
<?
endif;
?>
