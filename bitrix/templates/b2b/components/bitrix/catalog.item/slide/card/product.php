<div class="price-block">
    <p class="subtitle" id="<?= $itemIds['PRICE'] ?>"><?= $price['PRINT_RATIO_PRICE'] ?: '-' ?></p>
    <? if ($actualItem['CAN_BUY']): ?>
        <div data-entity="button-block" id="<?= $itemIds['BUY_LINK'] ?>">
            <div class="button-full" data-in-basket="N">
                <span>Купить</span>
            </div>
            <div style="display: none" class="button-variable bordered" data-in-basket="Y">
                <span>В корзине</span>
            </div>
        </div>
        <div style="display: none" class="quantity" data-entity="quantity-block"
             data-product="<?= $actualItem['ID'] ?>">
            <div class="icon icon-minus" data-value="1" id="<?= $itemIds['QUANTITY_DOWN'] ?>"></div>
            <input type="number" name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>" value="0"
                   id="<?= $itemIds['QUANTITY'] ?>">
            <div class="icon icon-plus" data-value="1" id="<?= $itemIds['QUANTITY_UP'] ?>"></div>
        </div>
    <? endif ?>
</div>

