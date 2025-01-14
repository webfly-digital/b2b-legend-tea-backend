<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>


    <?
    $previousLevel = 0;
    foreach ($arResult as $arItem):?>
        <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
            </div></div></div>
        <? endif ?>

        <? if ($arItem["IS_PARENT"]): ?>

            <div class="block <?=$arItem['PARAMS']['class']?> <?=$arItem["SELECTED"]?'active':''?>">
                <div class="drop-block">
                    <div class="icon-link">
                        <div class="icon <?=$arItem['PARAMS']['icon']?>"></div>
                        <span>Профиль</span>
                        <div class="icon icon-arrow-down"></div>
                    </div>
                    <div class="drop">
        <? else: ?>

            <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <div class="block <?=$arItem['PARAMS']['class']?> <?=$arItem["SELECTED"]?'active':''?>">
                    <a href="<?= $arItem["LINK"] ?>" class="icon-link">
                        <div class="icon <?=$arItem['PARAMS']['icon']?>"></div>
                        <span><?= $arItem["TEXT"] ?></span>
                    </a>
                </div>
            <? else: ?>
               <a href="<?= $arItem["LINK"] ?>" class="<?=$arItem["SELECTED"]?'active':''?>"><?= $arItem["TEXT"] ?></a>
            <? endif ?>

        <? endif ?>

        <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

    <? endforeach ?>

    <? if ($previousLevel > 1)://close last item tags?>
        </div></div></div>
    <? endif ?>


<? endif ?>