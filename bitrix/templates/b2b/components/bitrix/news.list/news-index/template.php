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
<? if ($arResult["ITEMS"]): ?>
    <section>
        <div class="section-title">
            <h2>Последние новости</h2>
            <a class="icon-link" href="/news/">
                <span>Все новости</span>
                <div class="icon icon-arrow-right"></div>
            </a>
        </div>
        <div class="news-card-group scroll">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <a class="news-card" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" href="/news/<?=$arItem["ID"]?>/">
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
                        <div class="card-text"><?=$arItem["PREVIEW_TEXT"]; ?>
                        </div>
                        <span class="link"> Подробнее </span>
                    </div>
                </a>
            <? endforeach; ?>
        </div>
    </section>
<? endif ?>
