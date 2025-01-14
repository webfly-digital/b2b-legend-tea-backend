<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

?>
<div class="container-size-0">
    <div class="icon-link mb-4">
        <div class="icon icon-arrow-left"></div>
        <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"><span>Назад</span></a>
    </div>
    <?
    if (!empty($arParams["~AUTH_RESULT"])):
        $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
        ?>
        <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
    <? endif ?>
    <h1 class="mb-4">Восстановление пароля</h1>
    <form name="bform" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>" class="custom-form">
        <? if ($arResult["BACKURL"] <> ''): ?>
            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
        <? endif ?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="SEND_PWD">

        <div class="form-group">
            <div class="form-col">
                <div class="form-row">
                    <div class="form-cell">
                        <label class="custom-input">
                            <input type="text" name="USER_LOGIN" maxlength="255" value="<?= $arResult["USER_LOGIN"] ?>" placeholder="E-mail" required/>
                            <input type="hidden" name="USER_EMAIL"/>
                            <div class="input-title">Электронная почта</div>
                            <div class="tip">Укажите почту использованную при регистрации. На нее мы вышлем ссылку для сброса пароля.</div>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-cell">
                        <input type="submit" class="button-full" name="send_account_info" value="Отправить ссылку"/>
                    </div>
                </div>

            </div>
        </div>
    </form>

</div>

<script type="text/javascript">
    document.bform.onsubmit = function () {
        document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;
    };
    document.bform.USER_LOGIN.focus();
</script>
