<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="container-size-3">
    <a href="<?= $arResult["URL_TO_LIST"] ?>" class="icon-link mb-6">
        <div class="icon icon-arrow-left"></div>
        <span>К списку заказов</span>
    </a>
    <div class="order-card">
        <? if ($arResult["ERROR_MESSAGE"] == ''): ?>
            <form method="post" action="<?= POST_FORM_ACTION_URI ?>">
                <input type="hidden" name="CANCEL" value="Y">
                <?= bitrix_sessid_post() ?>
                <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>">

                <div class="note-plate mb-6">
                    <p><?= GetMessage("SALE_CANCEL_ORDER1") ?>
                        <a href="<?= $arResult["URL_TO_DETAIL"] ?>"><?= GetMessage("SALE_CANCEL_ORDER2") ?>
                            #<?= $arResult["ACCOUNT_NUMBER"] ?></a>?</p>
                    <p><strong class="text-danger"><?= GetMessage("SALE_CANCEL_ORDER3") ?></strong></p>
                </div>

                <div class="custom-form">
                    <div class="form-group">
                        <div class="form-col">
                            <div class="form-row">
                                <div class="form-cell">
                                    <label class="custom-input">
                                        <textarea name="REASON_CANCELED" class="form-control" id="orderCancel"
                                                  rows="3"></textarea>
                                        <div class="input-title"><?= GetMessage("SALE_CANCEL_ORDER4") ?></div>
                                    </label>
                                </div>
                                <div class="form-cell"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-col">
                            <div class="form-row">
                                <div class="form-cell">
                                    <input type="submit" name="action" class="button-full"
                                           value="<?= GetMessage("SALE_CANCEL_ORDER_BTN") ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-col"></div>
                    </div>

                </div>

            </form>
        <? else: ?>
            <div class="note-plate">
                <?= ShowError($arResult["ERROR_MESSAGE"]); ?>
            </div>
        <? endif; ?>
    </div>
</div>