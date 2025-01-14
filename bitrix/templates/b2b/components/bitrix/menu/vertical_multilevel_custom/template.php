<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)):
    $previousLevel = 0; ?>
    <div class="catalog-nav collapsed d-mobile">
    <a href="" class="toggler">
        <div class="icon"></div>
    </a>
    <div class="catalog-nav-item">
        <a href="/catalog/new/" class="catalog-nav-link">
            <div class="icon icon-new"></div>
            <p>Новинки</p>
        </a>
    </div>
    <? foreach ($arResult    as $arItem):
    if(isset($arItem['CNT']) && $arItem['CNT'] == 0) continue;

    ?>
    <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
        <?= str_repeat("</div></div>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
    <? endif ?>

    <? if ($arItem["IS_PARENT"]): ?>
    <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
    <div class="catalog-nav-item">
    <div class="catalog-nav-toggler">
        <div class="icon icon-<?= $arItem['PARAMS']['ICON'] ?: 'equip' ?>"></div>
        <p><?= $arItem["TEXT"] ?></p>
    </div>
    <div class="catalog-nav-content" data-content="">
    <? else: ?>
    <div class="catalog-nav-item">
    <div class="catalog-nav-toggler">
        <div class="icon icon-<?= $arItem['PARAMS']['ICON'] ?: 'equip' ?>"></div>
        <p><?= $arItem["TEXT"] ?></p>
    </div>
    <div class="catalog-nav-content" data-content="">
    <? endif ?>
    <? else: ?>
        <? if ($arItem["PERMISSION"] > "D"): ?>
            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <div class="catalog-nav-item">
                    <a href="<?= $arItem["LINK"] ?>"
                       class="catalog-nav-link <? if ($arItem["SELECTED"]) echo "selected"; ?>">
                        <div class="icon icon-<?= $arItem['PARAMS']['ICON'] ?: 'equip' ?>"></div>
                        <p><?= $arItem["TEXT"] ?></p>
                    </a>
                </div>
            <? else: ?>
                <a href="<?= $arItem["LINK"] ?>" <? if ($arItem["SELECTED"]) { ?>class="selected"<? } ?>>
                    <div class="icon icon-<?= $arItem['PARAMS']['ICON'] ?: 'equip' ?>"></div>
                    <p><?= $arItem["TEXT"] ?></p>
                </a>
            <? endif ?>

        <? else: ?>

            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>

            <? else: ?>
                <a href="<?= $arItem["LINK"] ?>" <? if ($arItem["SELECTED"]) { ?>class="selected"<? } ?>>
                    <div class="icon icon-<?= $arItem['PARAMS']['ICON'] ?: 'equip' ?>"></div>
                    <p><?= $arItem["TEXT"] ?></p>
                </a>
            <? endif ?>

        <? endif ?>

    <? endif ?>

    <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

<? endforeach ?>

    <? if ($previousLevel > 1)://close last item tags?>
    <?= str_repeat("</div></div>", ($previousLevel - 1)); ?>
<? endif ?>

    </div>
<? endif ?>