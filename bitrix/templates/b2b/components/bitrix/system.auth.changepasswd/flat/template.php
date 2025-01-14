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

    <? if ($arResult["SHOW_FORM"]): ?>
        <h1 class="mb-4">Сменить пароль</h1>

        <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform" class="custom-form">
            <? if ($arResult["BACKURL"] <> ''): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? endif ?>
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="CHANGE_PWD">
            <input type="text" name="USER_LOGIN" maxlength="255" value="<?= $arResult["LAST_LOGIN"] ?>" hidden/>
            <input type="text" name="USER_CHECKWORD" maxlength="255" value="<?= $arResult["USER_CHECKWORD"] ?>" hidden/>

            <div class="form-group">
                <div class="form-col">

                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <input type="password" name="USER_PASSWORD" maxlength="255" value="<?= $arResult["USER_PASSWORD"] ?>" autocomplete="new-password" />
                                <div class="input-title">Новый пароль</div>
                                <div class="tip"><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></div>
                            </label>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" autocomplete="new-password" />
                                <div class="input-title">Подтвердите пароль</div>
                            </label>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-cell">
                            <input type="submit" class="button-full" name="change_pwd" value="Сохранить"/>
                        </div>
                    </div>


                </div>
            </div>


        </form>

        <script type="text/javascript">
            document.bform.USER_CHECKWORD.focus();
        </script>

    <? endif; ?>


</div>

