<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$emptyFilter = true;


foreach ($arResult["ITEMS"] as $key => $arItem) {
    if (!empty($arItem["VALUES"]) && !$arItem["PRICE"]) {
        $emptyFilter = false;
    }
}

if (!$emptyFilter) {
    ?>
    <form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"] ?>"
          method="get" class="filter" id="filter">

        <? foreach ($arResult["HIDDEN"] as $arItem): ?>
            <input type="hidden" name="<? echo $arItem["CONTROL_NAME"] ?>" id="<? echo $arItem["CONTROL_ID"] ?>"
                   value="<? echo $arItem["HTML_VALUE"] ?>"/>
        <? endforeach; ?>

        <div class="filter-toggler <?= $arResult['CHECKED'] ? 'active' : '' ?>">
            <div class="icon icon-filter"></div>
            <span>Фильтр</span>
        </div>

        <div class="filter-content">
            <div class="header">
                <h3>Фильтр</h3>
                <div class="icon icon-cross close"></div>
            </div>
            <div class="body simplebar-type1" data-simplebar data-simplebar-auto-hide="false">
                <?
                //not prices
                foreach ($arResult["ITEMS"] as $key => $arItem):
                    if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
                        continue;

                    /**
                     * skip: NUMBERS_WITH_SLIDER, NUMBERS, CALENDAR
                     */
                    if ($arItem["DISPLAY_TYPE"] == "A" || $arItem["DISPLAY_TYPE"] == "B" || $arItem["DISPLAY_TYPE"] == "U")
                        continue;

                    $arCur = current($arItem["VALUES"]);
                    ?>

                    <div class="group">
                        <div class="title">
                            <div class="subtitle"><?= $arItem["NAME"] ?></div>
                        </div>
                        <div class="checks">
                            <?
                            foreach ($arItem["VALUES"] as $val => $ar):?>
                                <label class="check <?= $ar["DISABLED"] ? 'disabled' : '' ?>"
                                       data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                       for="<? echo $ar["CONTROL_ID"] ?>">
                                    <input type="checkbox" value="<? echo $ar["HTML_VALUE"] ?>"
                                           name="<? echo $ar["CONTROL_NAME"] ?>"
                                           id="<? echo $ar["CONTROL_ID"] ?>" <?= $ar["CHECKED"] ? 'checked="checked"' : '' ?> <?= $ar["DISABLED"] ? 'disabled' : '' ?>
                                           onclick="smartFilter.click(this)">
                                    <div class="visible"><?= $ar["VALUE"]; ?></div>
                                </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                <?
                endforeach;
                ?>
            </div>

            <div class="footer">
                <button type="submit" class="button-full" id="set_filter" name="set_filter"> Применить</button>
                <button type="submit" class="button-full transparent" id="del_filter" name="del_filter"> Сбросить
                </button>
            </div>
        </div>
    </form>
    <? $this->SetViewTarget('FILTER_RESULTS'); ?>
    <? if ($arResult['CHECKED']): ?>
        <div class="bot">
            <div class="criteria">
                <? foreach ($arResult['CHECKED'] as $checked): ?>
                    <div class="item">
                        <div class="name"><?= $checked['PROPERTY']['NAME'] ?></div>
                        <div class="values">
                            <? foreach ($checked['VALUES'] as $value): ?>
                                <div class="value-item">
                                    <span><?= $value["VALUE"]; ?></span>
                                    <div class="icon filter-unchecked" data-id="<?= $value["CONTROL_ID"] ?>"></div>
                                </div>
                            <? endforeach ?>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
        </div>
    <? endif ?>
    <? $this->EndViewTarget(); ?>
    <script>
        var smartFilter;
        document.addEventListener("DOMContentLoaded", () => {
            smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
            window.initSimplebar();
        });
    </script>
<? } ?>