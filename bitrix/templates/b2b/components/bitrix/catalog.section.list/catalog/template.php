<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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


if (0 < $arResult["SECTIONS_COUNT"]):
    ?>
    <div class="catalog-main">
        <? foreach ($arResult['SECTIONS'] as &$arSection) {
            if (isset($arSection['CNT']) && $arSection['CNT'] == 0) continue;

            $countDeclension = new \Bitrix\Main\Grid\Declension('товар', 'товара', 'товаров');
            $declension = $countDeclension->get($arSection['CNT']); // return $declension = "примера"
            ?>
            <div class="catalog-main-item">
                <a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="catalog-main-item_head">
                    <div class="imgholder">
                        <div class="icon icon-<?= $arSection["ICON"] ?>"></div>
                    </div>
                    <p class="h5"><?= $arSection['NAME'] ?></p>
                    <p class="body"><?= $arSection['CNT'] ?> <?= $declension ?></p>
                </a>
                <? if ($arSection["LIST_SECTION"]): ?>
                    <div class="catalog-main-item_list">
                        <?
                        $index = 0;
                        foreach ($arSection["LIST_SECTION"] as $sect) {
                            if (isset($sect['CNT']) && $sect['CNT'] == 0) continue;

                            $index++; ?>
                            <a href="<?= $sect["SECTION_PAGE_URL"] ?>">
                                <div class="icon icon-<?= $sect['ICON'] ?>"></div>
                                <p><?= $sect['NAME'] ?></p>
                            </a>
                            <? if ($index == 10) break; ?>
                        <? } ?>
                    </div>
                    <? if ($index == 10): ?>
                        <a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="else-btn">Показать еще</a>
                    <? endif; ?>
                <? endif; ?>
            </div>
            <?
            ?>
        <? } ?>
    </div>
<? endif ?>
