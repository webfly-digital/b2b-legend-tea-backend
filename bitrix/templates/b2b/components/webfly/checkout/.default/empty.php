<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

?>
<div class="container-size-3">
    <div class="note-plate">
        <div class="bx-soa-empty-cart-text"><?= Loc::getMessage("EMPTY_BASKET_TITLE") ?></div>
        <?
        if (!empty($arParams['EMPTY_BASKET_HINT_PATH'])) {
            ?>
            <div class="bx-soa-empty-cart-desc">
                <?= Loc::getMessage(
                    'EMPTY_BASKET_HINT',
                    [
                        '#A1#' => '<a style="color:#E35553;" href="' . $arParams['EMPTY_BASKET_HINT_PATH'] . '">',
                        '#A2#' => '</a>',
                    ]
                ) ?>
            </div>
            <?
        }
        ?>
    </div>
</div>
