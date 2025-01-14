<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<? if ($arParams["~AUTH_RESULT"]["TYPE"] == "OK"): ?>
<?LocalRedirect('/auth/success/')?>
<? else: ?>
    <div class="container-size-2">
        <noindex>
            <?
            if (!empty($arParams["~AUTH_RESULT"])):
                $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
                ?>
                <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
            <? endif ?>

            <? if ($arResult["SHOW_EMAIL_SENT_CONFIRMATION"]): ?>
                <div class="alert alert-success"><? echo GetMessage("AUTH_EMAIL_SENT") ?></div>
            <? endif ?>

            <? if (!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"): ?>
                <div class="alert alert-warning"><? echo GetMessage("AUTH_EMAIL_WILL_BE_SENT") ?></div>
            <? endif ?>

            <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform" enctype="multipart/form-data"
                  class="custom-form">
                <input type="hidden" name="AUTH_FORM" value="Y"/>
                <input type="hidden" name="TYPE" value="REGISTRATION"/>
                <input type="text" name="USER_LOGIN" maxlength="255" value="<?= $arResult["USER_LOGIN"] ?>" hidden/>
                <div class="form-group">
                    <div class="form-row">
                        <h1>Регистрация оптового покупателя</h1>
                        <p class="mt-2 text">Вы уже клиент компании Легенда чая? <a
                                    href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                                    class=" text-red text-underline text-bold">Войдите
                                в аккаунт</a></p>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-col">
                        <div class="form-title">
                            <h3>Личные данные</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" placeholder="Введите фамилию" name="USER_LAST_NAME" minlength="2"
                                           maxlength="255" value="<?= $arResult["USER_LAST_NAME"] ?>" required>
                                    <div class="input-title">Фамилия*</div>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" name="USER_NAME" placeholder="Введите имя" minlength="2"
                                           maxlength="255" value="<?= $arResult["USER_NAME"] ?>" required>
                                    <div class="input-title"> Имя*</div>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" name="USER_SECOND_NAME" placeholder="Введите отчество"
                                           minlength="2"
                                           maxlength="255" value="<?= $arResult["USER_SECOND_NAME"] ?>">
                                    <div class="input-title"> Отчество</div>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="tel" name="USER_PERSONAL_PHONE" placeholder="+7" pattern=".{15,}"
                                           value="<?= $arResult["USER_PERSONAL_PHONE"] ?>" required>
                                    <div class="input-title"> Телефон*</div>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="email" name="USER_EMAIL" placeholder="E-mail" minlength="3"
                                           maxlength="255"
                                           value="<?= $arResult["USER_EMAIL"] ?>" required>
                                    <div class="input-title"> Электронная почта*</div>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell" id="location-row">
                                <label class="custom-input">
                                    <div class="input-title">Город</div>
                                    <?
                                    $locationTemplate = ($arParams['USE_AJAX_LOCATIONS'] !== 'Y') ? "popup" : "";
                                    CSaleLocation::proxySaleAjaxLocationsComponent(
                                        array(
                                            "AJAX_CALL" => "N",
                                            'CITY_OUT_LOCATION' => 'Y',
                                            'COUNTRY_INPUT_NAME' => 'PERSONAL_COUNTRY',
                                            "REGION_INPUT_NAME" => "PERSONAL_STATE",
                                            'CITY_INPUT_NAME' => 'UF_LOCATION_ID',
                                            'LOCATION_VALUE' => '',
                                        ),
                                        array(),
                                        $locationTemplate,
                                        true,
                                        'location-block-wrapper'
                                    );
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="password" name="USER_PASSWORD" placeholder="Введите пароль"
                                           minlength="<?= $arResult["GROUP_POLICY"]["PASSWORD_LENGTH"] ?: 3 ?>"
                                           maxlength="255" id="password" value="<?= $arResult["USER_PASSWORD"] ?>"
                                           autocomplete="off" required>
                                    <div class="input-title"> Пароль*</div>
                                    <? if ($arResult["GROUP_POLICY"]["PASSWORD_LENGTH"]): ?>
                                        <div class="tip">Не менее <?= $arResult["GROUP_POLICY"]["PASSWORD_LENGTH"] ?>
                                            символов
                                        </div>
                                    <? endif ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="password" name="USER_CONFIRM_PASSWORD" placeholder="Введите пароль"
                                           minlength="<?= $arResult["GROUP_POLICY"]["PASSWORD_LENGTH"] ?: 3 ?>"
                                           maxlength="255" id="confirm_password"
                                           value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" autocomplete="off"
                                           required>
                                    <div class="input-title"> Подтвердите пароль*</div>
                                    <? if ($arResult["GROUP_POLICY"]["PASSWORD_LENGTH"]): ?>
                                        <div class="tip">Не менее <?= $arResult["GROUP_POLICY"]["PASSWORD_LENGTH"] ?>
                                            символов
                                        </div>
                                    <? endif ?>
                                </label>
                            </div>
                        </div>

                        <? if ($arResult["USE_CAPTCHA"] == "Y"): ?>
                            <div class="form-row">
                                <div class="form-cell">
                                    <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                                    <label class="custom-input">
                                        <input type="text" name="captcha_word" maxlength="50" value=""
                                               autocomplete="off"/>
                                        <div class="input-title"><?= GetMessage("CAPTCHA_REGF_PROMT") ?>*</div>
                                        <div class="tip"><img
                                                    src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                                    width="180" height="40" alt="CAPTCHA"/></div>
                                    </label>
                                </div>
                            </div>
                        <? endif ?>
                    </div>
                    <div class="form-col">
                        <div class="form-title">
                            <h3>Данные вашей организации</h3>
                        </div>
                        <!--Я физическое лицо-->
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="checkbox-group mb-1">
                                    <input type="checkbox" name="INDIVIDUAL" class="custom-checkbox" id="INDIVIDUAL"
                                           data-control-group="g1" data-control-mode="master"
                                           value="y" <?= $arResult['INDIVIDUAL'] ? 'checked' : '' ?>>
                                    <span> Я физическое лицо </span>
                                </label>
                            </div>
                        </div>
                        <!--ИНН-->
                        <div class="form-row" data-control-group="g1" data-control-inverted="true"
                             data-control-type="display" data-control-mode="slave">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" id="INN_GET" name="INN" placeholder="1254781699" required=""
                                           pattern="[\d]{10,12}" value="<?= $arResult['INN'] ?>">
                                    <div class="input-title"> ИНН*</div>
                                    <div class="tip"> ИНН вы можете посмотреть в Свидетельстве о постановке на учет в
                                        налоговом органе
                                    </div>
                                </label>
                            </div>
                        </div>
                        <!--Подсказка по ИНН-->
                        <div id="INN_NOTE" class="form-row" style="display:none;" data-control-group="g1" data-control-inverted="true"
                             data-control-type="display" data-control-mode="slave">
                            <div class="form-cell">
                                <div class="note-plate green">
                                    <p>Название организации: <span id="inn_note-company"></span> </p>
                                    <p>Юридический адрес организации: <span id="inn_note-company_adr"></span></p>
                                </div>
                            </div>
                        </div>
                        <div id="EXIST_USER_NOTE" class="form-row" style="display:none;" data-control-group="g1" data-control-inverted="true"
                             data-control-type="display" data-control-mode="slave">
                            <div class="form-cell">
                                <div class="note-plate">
                                    <p> <span id="email_users"></span> <a href="/?forgot_password=yes">Восстановить пароль?</a></p>
                                </div>
                            </div>
                        </div>
                        <!--Название компании-->
                        <div id="COMPANY_ROW" class="form-row" data-control-group="g1" data-control-inverted="true"
                             data-control-type="display" data-control-mode="slave">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" name="COMPANY" placeholder="ООО «Компания»" required=""
                                           minlength="2" max-length="150" value="<?= $arResult['COMPANY'] ?>">
                                    <div class="input-title"> Юридическое название организации*</div>
                                    <div class="tip">Потребуется для выставления счета на оплату</div>
                                </label>
                            </div>
                        </div>
                        <!--Адрес компании-->
                        <div id="COMPANY_ADR_ROW" class="form-row" data-control-group="g1" data-control-inverted="true"
                             data-control-type="display" data-control-mode="slave">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" name="COMPANY_ADR"
                                           placeholder="123456, Москва, Варшавское шоссе, 124" minlength="2"
                                           max-length="150" value="<?= $arResult['COMPANY_ADR'] ?>">
                                    <div class="input-title"> Юридический адрес компании</div>
                                </label>
                            </div>
                        </div>
                        <!--ЭДО-->
                        <? if ($arResult['EDO_VALUES']): ?>
                            <div class="form-row" data-control-group="g1" data-control-inverted="true"
                                 data-control-type="display" data-control-mode="slave">
                                <div class="form-cell">
                                    <div class="custom-select">
                                        <label class="checkbox-group">
                                            <input type="checkbox" name="USE_EDO" class="custom-checkbox"
                                                   data-control-group="g2" data-control-mode="master"
                                                   value="y" <?= $arResult['USE_EDO'] ? 'checked' : '' ?>>
                                            <span> Работаю по ЭДО </span>
                                        </label>
                                        <select name="EDO" required="" data-control-group="g2"
                                                data-control-inverted="false" data-control-mode="slave" class=""
                                                disabled="">
                                            <option value="" <?= !$arResult['EDO'] ? 'selected' : '' ?>> Выберите</option>
                                            <?foreach ($arResult['EDO_VALUES'] as $value):?>
                                                <option value="<?=$value["VALUE"]?>" <?= $arResult['EDO'] ? 'selected' : '' ?>><?=$value["NAME"]?></option>
                                            <?endforeach?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <? endif ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-col">
                        <div class="form-row">
                            <div class="form-cell">
                                <input type="submit" class="button-full" name="Register" value="Зарегистрироваться" id="REGISTER_BTN">
                                <p class="text mt-2"> Нажимая Зарегистрироваться, я принимаю изложенное в следующих
                                    документах: <a href="/rules/" class="link">Условиями продажи</a>, <a href="/rules/#83810" class="link">Условия
                                        обработки платежей</a>, <a href="/userconsent/" class="link">Политика
                                        конфиденциальности</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-col"></div>
                </div>

            </form>

        </noindex>
    </div>
    <script>
        let b2bRegister = new B2BRegister();
        b2bRegister.init({
            ajaxUrl: '<?=CUtil::JSEscape($templateFolder."/ajax.php")?>'
        });
    </script>
<? endif ?>
