<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->addExternalJs($templateFolder . '/js/validation.js');
if (!empty($arResult['ERRORS'])) {
    if (isset($arResult['ERRORS'][$component::E_NOT_AUTHORIZED])) {
        $authListGetParams = array();
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }
}


?>
<div class="container-size-3">
    <div class="container-size-2 ml-0">
        <div class="icon-link mb-6">
            <div class="icon icon-arrow-left"></div>
            <a href="<?= $arParams["PATH_TO_LIST"] ?>"><span>Список компаний</span></a>
        </div>

        <?
        if ($arResult["ID"] <> '') {
            ShowError($arResult["ERROR_MESSAGE"]);
            ?>
            <form method="post" class="custom-form" action="<?= POST_FORM_ACTION_URI ?>" enctype="multipart/form-data"
                  id="sale-profile_form">
                <?= bitrix_sessid_post() ?>
                <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>" id="ID_PROFILE">
                <input type="hidden" name="PERSON_TYPE_ID" value="<?= $arResult["PERSON_TYPE_ID"] ?>">
                <input type="hidden"  class="button-variable" name="save"
                       value="Сохранить">
                <? $row = ['COMPANY_TITLE'];
                foreach ($row as $code) {
                    $property = $arResult["ORDER_FIELDS"][$code];
                    $name = "ORDER_PROP_" . $property['ID'];
                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                    $cellClass = '';
                    $type = '';
                    ?>
                    <input data-code="<?= $code ?>" type="hidden" name="<?= $name ?>" value="<?= $currentValue ?>">
                    <?
                } ?>
                <div class="form-group">
                    <div class="form-row">
                        <h1><?= $arResult["ID"] == 'NEW' ? 'Создание компании' : 'Изменение компании' ?></h1>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-col">
                        <div class="form-title">
                            <h3>Данные профиля</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <input type="text" name="NAME" maxlength="50"
                                           id="sale-personal-profile-detail-name" <?= $arResult["ID"] == 'NEW' ? '' : "readonly" ?>
                                           value="<?= $arResult["ID"] == 'NEW' ? '' : $arResult["NAME"] ?>" required
                                           minlength="2"/>
                                    <div class="input-title"> Название профиля</div>
                                </label>
                            </div>
                            <div class="form-cell"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <div class="custom-select">
                                    <select id="PERSON_TYPE_SWITCH" <?= ($arResult['ID'] == 'NEW') ? '' : 'disabled' ?>>
                                        <option value="<?= B2B_UR_PERSON_TYPE_ID ?>" <?= $arResult["PERSON_TYPE_ID"] == B2B_UR_PERSON_TYPE_ID ? 'selected' : '' ?>>
                                            ООО - ИП
                                        </option>
                                        <option value="<?= B2B_FIZ_PERSON_TYPE_ID ?>" <?= $arResult["PERSON_TYPE_ID"] == B2B_FIZ_PERSON_TYPE_ID ? 'selected' : '' ?>>
                                            Физическое лицо
                                        </option>
                                    </select>
                                    <div class="input-title"> Тип профиля</div>
                                </div>
                            </div>
                            <div class="form-cell"></div>
                        </div>
                    </div>
                </div>
                <? if ($arResult["PERSON_TYPE_ID"] == B2B_UR_PERSON_TYPE_ID): ?>
                    <div class="form-group">
                        <div class="form-col">
                            <div class="form-title">
                                <h3>Данные компании</h3>
                                <div class="subtitle">Будут использоваться для выставления счетов</div>
                            </div>
                            <div class="form-grid">
                                <?
                                $readOnly = !empty($currentValue) ? true : false;
                                $property = $arResult["ORDER_FIELDS"]['INN'];
                                $name = "ORDER_PROP_" . $property['ID'];
                                $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                $cellClass = '';
                                $code = 'INN';
                                include 'field.php';
                                ?>
                                <? if (empty($currentValue)): ?>
                                    <div id="INN_NOTE_YELLOW" class="notify-yellow">Данные о названии и адресе компании
                                        заполнятся автоматически после ввода ИНН
                                    </div>
                                    <div id="INN_NOTE" class="form-cell" style="display:none;" data-control-group="g1"
                                         data-control-inverted="true"
                                         data-control-type="display" data-control-mode="slave">
                                        <div class="form-cell">
                                            <div class="note-plate green">
                                                <p>Название организации: <span id="inn_note-company"></span></p>
                                                <p>Юридический адрес организации: <span
                                                            id="inn_note-company_adr"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="EXIST_USER_NOTE" class="form-row" style="display:none;">
                                        <div class="form-cell">
                                            <div class="note red">
                                                <span class="text-red" id="email_users"></span>
                                            </div>
                                        </div>
                                    </div>
                                <? endif; ?>
                                <?
                                $readOnly = false;
                                $property = $arResult["ORDER_FIELDS"]['COMPANY_TITLE'];
                                $name = "ORDER_PROP_" . $property['ID'];
                                $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                $cellClass = '';
                                $code = 'COMPANY_TITLE';
                                include 'field.php'; ?>
                                <?
                                $readOnly = false;
                                $property = $arResult["ORDER_FIELDS"]['COMPANY_ADR'];
                                $name = "ORDER_PROP_" . $property['ID'];
                                $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                $cellClass = 'big';
                                $code = 'COMPANY_ADR';
                                include 'field.php';
                                ?>
                                <div class="form-cell edo">
                                    <div class="custom-select">
                                        <?
                                        $property = $arResult["ORDER_FIELDS"]['EDO_USE'];
                                        $name = "ORDER_PROP_" . $property['ID'];
                                        $currentValue = $arResult["ORDER_PROPS_VALUES"][$name]; ?>
                                        <label class="checkbox-group">
                                            <input type="checkbox" name="<?= $name ?>" value="Y"
                                                   id="sppd-property-<?= $property['ID'] ?>"
                                                   class="custom-checkbox" data-control-group="g2"
                                                   data-control-mode="master" <? if ($currentValue == "Y" || !isset($currentValue) && $property["DEFAULT_VALUE"] == "Y") echo " checked"; ?>>
                                            <span> <?= $property['NAME'] ?> </span>
                                        </label>
                                        <?
                                        $property = $arResult["ORDER_FIELDS"]['EDO'];
                                        $name = "ORDER_PROP_" . $property['ID'];
                                        $currentValue = $arResult["ORDER_PROPS_VALUES"][$name]; ?>

                                        <select name="<?= $name ?>" data-control-group="g2"
                                                data-control-inverted="false" data-control-mode="slave">
                                            <option value="0">Выберите</option>
                                            <? foreach ($property["VALUES"] as $value): ?>
                                                <option value="<?= $value["VALUE"] ?>" <? if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " selected" ?>><?= $value["NAME"] ?></option>
                                            <? endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif ?>
                <? if ($arResult["PERSON_TYPE_ID"] == B2B_FIZ_PERSON_TYPE_ID): ?>
                    <div class="form-group">
                        <div class="form-col">
                            <div class="form-title">
                                <h3>Контактное лицо</h3>
                            </div>
                            <div class="form-row">
                                <div class="form-cell">
                                    <!--Я и есть контактное лицо-->
                                    <?
                                    $readOnly = false;
                                    $code = 'USER_IS_CONTACT';
                                    $property = $arResult["PROPERTY_LIST_FULL"][$code];
                                    $currentValue = $arResult["ELEMENT_PROPERTIES"][$code]['VALUE'];
                                    if ($currentValue) {
                                        $readOnly = true;
                                    }

                                    if ($arResult["PERSON_TYPE_ID"] != B2B_FIZ_PERSON_TYPE_ID) {
                                        ?>
                                        <label class="checkbox-group">
                                            <input id="<?= $code ?>" value='<?= current($property['ENUM'])['ID'] ?>'
                                                   type="checkbox" name="PROPERTY[<?= $property['ID'] ?>]"
                                                   class="custom-checkbox" <?= in_array($currentValue, array_keys($arResult["PROPERTY_LIST_FULL"][$code]['ENUM'])) ? 'checked' : '' ?>>
                                            <span> <?= $property['NAME'] ?> </span>
                                        </label>
                                    <? }
                                    ?>

                                </div>
                            </div>

                            <div class="form-row">
                                <? $row = ['COMPANY_UF_COMPANY_NAME', 'COMPANY_EMAIL'];
                                foreach ($row as $code) {
                                    $property = $arResult["ORDER_FIELDS"][$code];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $cellClass = '';
                                    $type = $code == 'CONTACT_EMAIL' ? 'email' : '';
                                    if ($code == 'COMPANY_EMAIL') {
                                        $readOnly = !empty($currentValue) ? true : false;
                                    }
                                    include 'field.php';
                                    unset($readOnly);
                                } ?>
                            </div>
                            <div class="form-row">
                                <? $row = ['COMPANY_UF_COMPANY_LAST_NAME', 'COMPANY_PHONE'];
                                foreach ($row as $code) {
                                    $property = $arResult["ORDER_FIELDS"][$code];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $cellClass = '';
                                    $type = $code == 'CONTACT_PHONE' ? 'tel' : '';
                                    $pattern = $code == 'CONTACT_PHONE' ? '.{15,}' : '';
                                    $pattern = '';
                                    if (!empty($currentValue) && $code == 'COMPANY_PHONE') $readOnly = true;
                                    include 'field.php';
                                    $readOnly = false;
                                } ?>
                            </div>
                            <div class="form-row">
                                <? $row = ['COMPANY_UF_COMPANY_SECOND_NAME'];
                                foreach ($row as $code) {
                                    $property = $arResult["ORDER_FIELDS"][$code];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $cellClass = '';
                                    $type = '';
                                    $pattern = '';
                                    include 'field.php';
                                }
                                $readOnly = false; ?>
                                <div class="form-cell">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="EXIST_USER_NOTE" class="form-group form-row" style="display:none;">
                            <div class="form-cell">
                                <div class="note red">
                                    <span class="text-red" id="email_users"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif ?>
                <div class="form-group">
                    <div class="form-col">
                        <div class="form-title">
                            <h3>Доставка</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <?
                                    $property = $arResult["ORDER_FIELDS"]['PACK'];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name]; ?>
                                    <select name="<?= $name ?>">
                                        <option value="0">Выберите</option>
                                        <? foreach ($property["VALUES"] as $value): ?>
                                            <option value="<?= $value["VALUE"] ?>" <? if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " selected" ?>><?= $value["NAME"] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <div class="input-title"><?= $property['NAME'] ?></div>
                                </label>
                            </div>
                            <div class="form-cell">
                                <label class="custom-input">
                                    <?
                                    $property = $arResult["ORDER_FIELDS"]['STICKERS'];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name]; ?>
                                    <select name="<?= $name ?>">
                                        <option value="0">Выберите</option>
                                        <? foreach ($property["VALUES"] as $value): ?>
                                            <option value="<?= $value["VALUE"] ?>" <? if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " selected" ?>><?= $value["NAME"] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <div class="input-title"><?= $property['NAME'] ?></div>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-cell">
                                <label class="custom-input">
                                    <?
                                    $property = $arResult["ORDER_FIELDS"]['DOCUMENTS'];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name]; ?>
                                    <select name="<?= $name ?>">
                                        <option value="0">Выберите</option>
                                        <? foreach ($property["VALUES"] as $value): ?>
                                            <option value="<?= $value["VALUE"] ?>" <? if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " selected" ?>><?= $value["NAME"] ?></option>
                                        <? endforeach ?>
                                    </select>
                                    <div class="input-title"><?= $property['NAME'] ?></div>
                                </label>
                            </div>
                            <div class="form-cell">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-col">
                        <div class="form-title">
                            <h3>Адрес доставки</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-cell" id="location-row">
                                <label class="custom-input">
                                    <div class="input-title">Город*</div>
                                    <?
                                    $property = $arResult["ORDER_FIELDS"]['LOCATION'];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $locationTemplate = ($arParams['USE_AJAX_LOCATIONS'] !== 'Y') ? "popup" : "";
                                    $locationClassName = 'location-block-wrapper';
                                    if ($arParams['USE_AJAX_LOCATIONS'] === 'Y') {
                                        $locationClassName .= ' location-block-wrapper-delimeter';
                                    }
                                    $locationValue = (int)($currentValue) ? (int)$currentValue : $property["DEFAULT_VALUE"];

                                    CSaleLocation::proxySaleAjaxLocationsComponent(
                                        array(
                                            "AJAX_CALL" => "N",
                                            'CITY_OUT_LOCATION' => 'Y',
                                            'COUNTRY_INPUT_NAME' => $name . '_COUNTRY',
                                            'CITY_INPUT_NAME' => $name,
                                            'LOCATION_VALUE' => $locationValue,
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
                            <? $row = ['ZIP', 'ADDRESS'];
                            foreach ($row as $code) {
                                $property = $arResult["ORDER_FIELDS"][$code];
                                $name = "ORDER_PROP_" . $property['ID'];
                                $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                $cellClass = '';
                                include 'field.php';
                            } ?>
                        </div>
                        <div class="form-row">
                            <? $row = ['ADDRESS_COMMENT'];
                            foreach ($row as $code) {
                                $property = $arResult["ORDER_FIELDS"][$code];
                                $name = "ORDER_PROP_" . $property['ID'];
                                $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                $cellClass = '';
                                include 'field.php';
                            } ?>
                        </div>
                    </div>
                </div>
                <? if ($arResult["PERSON_TYPE_ID"] == B2B_UR_PERSON_TYPE_ID): ?>
                    <div class="form-group">
                        <div class="form-col">
                            <div class="form-title">
                                <h3>Публичные данные компании</h3>
                            </div>
                            <div class="form-row">
                                <?
                                $row = ['PUBLIC_NAME', 'PUBLIC_SITE'];
                                foreach ($row as $code) {
                                    $property = $arResult["ORDER_FIELDS"][$code];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $cellClass = '';
                                    include 'field.php';
                                }
                                ?>
                            </div>
                            <div class="form-row">
                                <?
                                $row = ['PUBLIC_EMAIL', 'PUBLIC_PHONE'];
                                foreach ($row as $code) {
                                    $property = $arResult["ORDER_FIELDS"][$code];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $cellClass = '';
                                    include 'field.php';
                                }
                                ?>
                            </div>
                            <div class="form-row">
                                <?
                                $row = ['PUBLIC_ADDRESS', 'PUBLIC_COORD'];
                                foreach ($row as $code) {
                                    $property = $arResult["ORDER_FIELDS"][$code];
                                    $name = "ORDER_PROP_" . $property['ID'];
                                    $currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
                                    $cellClass = '';
                                    include 'field.php';
                                }

                                ?>
                            </div>

                            <div class="form-row">
                                <div class="form-cell">
                                    <!-- после дропа файл будет валяться в window.fileFromDropzone -->
                                    <!-- после выбора ручками через диалог на всякий случай тоже -->
                                    <label class="dropzone">
                                        <input type="file" accept="image/png, image/jpeg" name="ORDER_PROP_145[]">
                                        <div class="inner">
                                            <div class="placeholder <?= current($arResult["ORDER_PROPS_VALUES"]['ORDER_PROP_145'])["SRC"] ? 'hidden' : '' ?>">
                                                <div class="icon icon-plus"></div>
                                                <span>Добавить логотип</span>
                                            </div>
                                            <div class="img">
                                                <img src="<?=current($arResult["ORDER_PROPS_VALUES"]['ORDER_PROP_145'])["SRC"] ?: '' ?>"
                                                     alt="">
                                            </div>
                                        </div>
                                        <input type="text" name="FILE_REMOVE" value="" hidden>
                                        <input type="text" name="ORDER_PROP_145_del" value="" data-id-file="<?=current($arResult["ORDER_PROPS_VALUES"]['ORDER_PROP_145'])["ID"] ?: '' ?>" hidden>
                                    </label>
                                </div>
                                <div class="form-cell">

                                </div>
                            </div>

                        </div>
                    </div>
                <? endif ?>

                <div class="form-group">
                    <div class="form-col">
                        <div class="form-row">
                            <div class="form-cell text-right">
                                <input type="submit" id="SAVE_BTN" class="button-variable" name="save"
                                       value="Сохранить">
                                <a class="button-variable bordered" href="<?= $arParams["PATH_TO_LIST"] ?>">Отменить</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?
        } else {
            ShowError($arResult["ERROR_MESSAGE"]);
        }
        ?>
    </div>
</div>
<script>
    window.addEventListener('b24:form:init', (event) => {
        let form = event.detail.object;
        if (form.identification.id == 28) {
            form.setProperty("my_param1", "Задвоение ИНН у клиента");
        }
    });
</script>
<script data-b24-form="click/28/cmqscl" data-skip-moving="true">(function (w, d, u) {
        var s = d.createElement('script');
        s.async = true;
        s.src = u + '?' + (Date.now() / 180000 | 0);
        var h = d.getElementsByTagName('script')[0];
        h.parentNode.insertBefore(s, h);
    })(window, document, 'https://crm.legend-tea.ru/upload/crm/form/loader_28_cmqscl.js');</script>
<button id="BX_FORM" style="display: none">задать вопрос</button>
<script>
    new JSWebflySaleProfile();
</script>


