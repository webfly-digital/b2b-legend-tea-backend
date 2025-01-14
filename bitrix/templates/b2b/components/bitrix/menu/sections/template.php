<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="catalog-nav collapsed d-mobile">
        <a href="" class="toggler">
            <div class="icon"></div>
        </a>
        <?
        foreach ($arResult as $arItem):
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue;
            ?>
            <a href="<?= $arItem["LINK"] ?>" class="item <?=$arItem["SELECTED"]?'active':''?>">
                <div class="icon icon-new-<?=$arItem['PARAMS']['ICON']?:'equip'?>"></div>
                <span> <?= $arItem["TEXT"] ?> </span>
            </a>

        <? endforeach ?>

    </div>
<? endif ?>
