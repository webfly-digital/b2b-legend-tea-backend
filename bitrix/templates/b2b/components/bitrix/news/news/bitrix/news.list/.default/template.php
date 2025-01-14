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
?>
<div class="news-card-group <?= $arParams["SHOW_DETAIL"] == 'Y' ? 'scroll' : '' ?>">
    <? if ($arResult["ITEMS"]): ?>
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>" class="news-card"
               id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <div class="card-img">
                    <div class="content">
                        <? if ($arItem["PICTURE"]["src"]): ?>
                            <img src="<?= $arItem["PICTURE"]["src"] ?>"
                                 alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                 title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                        <? endif ?>
                    </div>
                </div>
                <div class="card-content">
                    <div class="date"><? echo $arItem["DISPLAY_ACTIVE_FROM"] ?></div>
                    <div class="card-title">
                        <h5><? echo $arItem["NAME"] ?></h5>
                    </div>
                    <div class="card-text"><? echo $arItem["PREVIEW_TEXT"]; ?></div>
                    <div class="link"> Подробнее</div>
                </div>
            </a>
        <? endforeach; ?>
    <? else: ?>
        <h4>Список новостей пуст:(</h4>
    <? endif ?>
</div>

<? if ($arParams["DISPLAY_BOTTOM_PAGER"] && $arParams["SHOW_DETAIL"] != 'Y'): ?>
    <section></section>
    <?= $arResult["NAV_STRING"] ?>
<? endif; ?>
