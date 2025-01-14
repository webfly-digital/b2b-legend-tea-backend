<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

?>
<? $APPLICATION->SetPageProperty('template_class', 'hidden'); ?>
<div class="login-page">
    <div class="content">
        <div class="logo">
            <a href="/"><img src="<?= SITE_TEMPLATE_PATH ?>/assets/static/img/logo.png" alt=""></a>
        </div>

        <?
        if (!empty($arParams["~AUTH_RESULT"])):
            $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
            ?>
            <div class="alert alert-danger"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? endif ?>

        <?
        if ($arResult['ERROR_MESSAGE'] <> ''):
            $text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
            ?>
            <div class="alert alert-danger"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? endif ?>
        <div class="custom-form">
            <?
            $APPLICATION->IncludeComponent(
                "bxmaker:authuserphone.enter",
                ".default",
                array(
                    "REGISTER_URL" => SITE_DIR . "auth/registration/?register=yes",
                    "PROFILE_URL" => SITE_DIR . "auth/",
                    "FORGOT_PASSWORD_URL" => SITE_DIR . "auth/forgot-password/?forgot-password=yes",
                    "AUTH_URL" => SITE_DIR . "auth/",
                    "BACKURL" => ((isset($_REQUEST['backurl']) && $_REQUEST['backurl']) ? $_REQUEST['backurl'] : "")
                )
            ) ?>
            <div class="yellow-notify" id="yellow-notify">
                <p>Внимание, сервис работает нестабильно.<br>Можете
                    <button id="enter-auth-form__by">войти с помощью пароля</button>
                </p>
                <div class="icon icon-cross close" style="background: #979797;"></div>
            </div>
            <div class="form-group">
                <div class="form-col">
                    <div class="form-row">
                        <div class="form-cell">
                            <div class="text-center">
                                <p class="text">Вы еще не клиент компании Легенда чая? </p>
                                <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>?register=yes"
                                   class="mt-1 db text text-red text-underline text-bold">Зарегистрируйтесь</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .bxmaker-authuserphone-enter-auth__toregistration {
        display: none
    }

    .bxmaker-authuserphone-enter-auth-form__by {
        display: none;
    }
</style>
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", (event) => {
        setTimeout(function () {
            let elemAuthForm = document.querySelector('.bxmaker-authuserphone-enter-auth')
            let yellowNotify = BX('yellow-notify');

            if (elemAuthForm && yellowNotify) {
                let btn = BX('enter-auth-form__by');

                if (btn) {
                    let bxmakerAuthLink = document.querySelector('.bxmaker-authuserphone-enter-botcall__notice .bxmaker-authuserphone-link');
                    let bxmakerEnterAuth = document.querySelector('.bxmaker-authuserphone-enter-auth-form__by a');
                    if (bxmakerEnterAuth) {
                        btn.addEventListener('click', function () {
                            let bxmakerAuthBotcall = document.querySelector('.bxmaker-authuserphone-enter-botcall');
                            if (bxmakerAuthBotcall && bxmakerAuthBotcall.style.display !== 'none') {
                                bxmakerAuthLink.click();
                            }

                            bxmakerEnterAuth.click();
                        })
                    }

                }


                let observer = new MutationObserver(mutationRecords => {
                    let stateNotify = [];
                    mutationRecords.forEach((record) => {

                        if (record.attributeName == "style") {

                            if (record.target.className == 'bxmaker-authuserphone-enter-botcall') {
                                if (record.oldValue == "display: none;") {
                                    stateNotify.push('display');
                                } else {
                                    stateNotify.push('none');
                                }
                            }
                            if (record.target.className == 'bxmaker-authuserphone-enter-auth-form') {
                                if (record.oldValue == "display: none;") {
                                    stateNotify.push('display');
                                } else {
                                    stateNotify.push('none');
                                }
                            }

                        }

                    })

                    for (const key in stateNotify) {
                        if (stateNotify.hasOwnProperty(key)) {
                            if (stateNotify[key] == 'display') {
                                yellowNotify.style.display = '';
                                break;
                            }else{
                                yellowNotify.style.display = 'none';
                            }
                        }

                    }


                });
                observer.observe(elemAuthForm, {
                    subtree: true,
                    attributes: true,
                    characterDataOldValue: true,
                    attributeOldValue: true
                });


            }

        }, 100)
    })



    <?if ($arResult["LAST_LOGIN"] <> ''):?>
    try {
        document.form_auth.USER_PASSWORD.focus();
    } catch (e) {
    }
    <?else:?>
    try {
        document.form_auth.USER_LOGIN.focus();
    } catch (e) {
    }
    <?endif?>
</script>

