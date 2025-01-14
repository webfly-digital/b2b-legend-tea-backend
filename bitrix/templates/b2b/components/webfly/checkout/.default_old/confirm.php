<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */
$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
?>
<div class="container-size-3">
    <h1><? $APPLICATION->showTItle(false) ?></h1>
    <? if (!empty($arResult["ORDER"])): ?>
    <br>
        <div class="note-plate">
            <p>Ваш заказ <b>№<?= $arResult["ORDER"]["ACCOUNT_NUMBER"] ?></b> успешно создан.</p>
            <p>Вы можете следить за выполнением своего заказа в <a href="<?= $arParams["PATH_TO_PERSONAL"] ?>">Персональном
                    разделе сайта</a>.</p>
        </div>
    <br>
    <?
    if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y')
    {
    if (!empty($arResult["PAYMENT"]))
    {
    foreach ($arResult["PAYMENT"] as $payment)
    {
    if ($payment["PAID"] != 'Y')
    {
    if (!empty($arResult['PAY_SYSTEM_LIST'])
    && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
    )
    {
    $arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];

    if (empty($arPaySystem["ERROR"]))
    {
    ?>
        <div class="sale_order_full_table">
            <?
            if ($arResult['ORDER']["STATUS_ID"] == 'N'):?>
                <div class="alert alert-danger" role="alert">Вы сможете оплатить заказ после проверки менеджером. Сразу после проверки вы получите письмо с инструкциями по оплате. Оплатить заказ можно будет в персональном разделе сайта.</div>
            <? else:?>
                <? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
                    <?
                    $orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
                    $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
                    ?>
                    <script>
                        window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                    </script>
                <?= Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&PAYMENT_ID=" . $paymentAccountNumber)) ?>
                <? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
                <br/>
                    <?= Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&pdf=1&DOWNLOAD=Y")) ?>
                <? endif ?>
                <? else: ?>
                    <?= $arPaySystem["BUFFERED_OUTPUT"] ?>
                <? endif ?>
            <? endif ?>
        </div>

        <?
    }
    else {
        ?>
        <span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></span>
        <?
    }
    }
    else {
        ?>
        <span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></span>
        <?
    }
    }
    }
    }
    }
    else {
        ?>
    <br/><strong><?= $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] ?></strong>
    <?
    }
    ?>
        <script>(window.b24order = window.b24order || []).push({
                id: "<?=$arResult["ORDER"]['ID']?>",
                sum: "<?=$arResult["ORDER"]['PRICE']?>"
            });</script>
    <? else: ?>

        <b><?= Loc::getMessage("SOA_ERROR_ORDER") ?></b>
    <br/><br/>
        <div class="note-plate">
            <p> <?= Loc::getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])]) ?>
                <?= Loc::getMessage("SOA_ERROR_ORDER_LOST1") ?></p>
        </div>
    <? endif ?>
</div>

