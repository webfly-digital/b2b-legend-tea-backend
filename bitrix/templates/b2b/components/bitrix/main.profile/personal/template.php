<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="mid">
    <div class="container-size-2">
        <? ShowError($arResult["strProfileError"]); ?>
        <?
        if ($arResult['DATA_SAVED'] == 'Y'):?>
            <div class="note-plate green"><?= ShowNote(GetMessage('PROFILE_DATA_SAVED')); ?></div>
        <?
        endif;
        ?>

        <form class="custom-form" method="post" name="form1" action="<?= $arResult["FORM_TARGET"] ?>"
              enctype="multipart/form-data">
            <?= $arResult["BX_SESSION_CHECK"] ?>
            <input type="hidden" name="lang" value="<?= LANG ?>"/>
            <input type="hidden" name="ID" value=<?= $arResult["ID"] ?>/>

            <div class="form-group">
                <div class="form-row">
                    <h1>Личные данные</h1>
                </div>
            </div>

            <div class="form-group">
                <div class="form-col">
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <input type="text" name="LAST_NAME" maxlength="50" minlength="3"
                                       value="<?= $arResult["arUser"]["LAST_NAME"] ?>" placeholder="Введите фамилию"
                                       required/>
                                <div class="input-title"> Фамилия</div>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <input type="text" name="NAME" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>"
                                       placeholder="Введите имя" required minlength="3"/>
                                <div class="input-title"> Имя</div>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <input type="text" name="SECOND_NAME" maxlength="50"
                                       value="<?= $arResult["arUser"]["SECOND_NAME"] ?>" placeholder="Введите отчество"
                                       minlength="3"/>
                                <div class="input-title"> Отчество</div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <!-- для телефона вместо minlength указывать pattern -->
                                <input type="tel" required name="PERSONAL_PHONE" placeholder="+7" pattern=".{15,}"
                                       maxlength="255" value="<?= $arResult["arUser"]["PERSONAL_PHONE"] ?>"/>
                                <div class="input-title"> Телефон</div>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-input">
                                <input type="email" name="EMAIL" placeholder="E-mail" minlength="3" maxlength="50"
                                       value="<? echo $arResult["arUser"]["EMAIL"] ?>" required/>
                                <? //todo login?>
                                <div class="input-title"> Электронная почта</div>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-cell">
                            <label class="custom-select">
                                <select name="UF_BREGION">
                                    <option value="" disabled <?=empty($arResult["arUser"]["UF_BREGION"])?'selected':''?>>Выберите регион или город</option>
                                    <? foreach ($arResult["REGION"] as $key => $region): ?>
                                        <option value="<?=$key?>" <?=$arResult["arUser"]["UF_BREGION"] == $key?'selected':''?>><?=$region?></option>
                                    <? endforeach; ?>
                                </select>
                                <div class="input-title">Регион или город</div>
                            </label>
                        </div>
                    </div>

                    <!--                    <div class="form-row">-->
                    <!--                        <div class="form-cell">-->
                    <!--                            <label class="custom-input">-->
                    <!--                                <div class="input-title-city"> Город</div>-->
                    <!--                                --><? //
                    //                                global $USER;
                    //                                if ($USER->IsAdmin()) {
                    //                                    $APPLICATION->IncludeComponent("bitrix:sale.location.selector.search", "b2b", array(
                    //                                        "COMPONENT_TEMPLATE" => ".default",
                    //                                        "ID" => "",    // ID местоположения
                    //                                        "CODE" => $arResult["arUser"]["UF_LOCATION_CODE"] ?:"",    // Символьный код местоположения
                    //                                        "INPUT_NAME" => "UF_LOCATION_CODE",    // Имя поля ввода
                    //                                        "PROVIDE_LINK_BY" => "code",    // Сохранять связь через
                    //                                        "FILTER_BY_SITE" => "N",    // Фильтровать по сайту
                    //                                        "SHOW_DEFAULT_LOCATIONS" => "N",    // Отображать местоположения по-умолчанию
                    //                                        "CACHE_TYPE" => "A",    // Тип кеширования
                    //                                        "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                    //                                        "JS_CONTROL_GLOBAL_ID" => "",    // Идентификатор javascript-контрола
                    //                                        "JS_CALLBACK" => "",    // Javascript-функция обратного вызова
                    //                                        "SUPPRESS_ERRORS" => "N",    // Не показывать ошибки, если они возникли при загрузке компонента
                    //                                        "INITIALIZE_BY_GLOBAL_EVENT" => "",    // Инициализировать компонент только при наступлении указанного javascript-события на объекте window.document
                    //                                        "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
                    //                                        "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
                    //                                    ),
                    //                                        false
                    //                                    );
                    //                                }
                    //                                ?>
                    <!--                            </label>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </div>
            </div>

            <div class="form-group">
                <div class="form-col">
                    <div class="form-row">
                        <div class="form-cell">
                            <input type="submit" name="save" value="Сохранить" class="button-variable">
                            <input type="reset" value="Отменить" class="button-variable bordered">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>