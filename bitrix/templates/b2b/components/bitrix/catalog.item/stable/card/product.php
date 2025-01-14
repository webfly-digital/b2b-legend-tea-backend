<div class="row">
    <? if ($primaryPropertyCode): ?>
        <div class="product-table-cell">
            <span><?= $actualItem['PROPERTIES'][$primaryPropertyCode]['VALUE'] ?: '-' ?></span>
        </div>
    <? endif ?>
    <div class="product-table-cell" data-entity="price-block">
        <span id="<?= $itemIds['PRICE'] ?>"><?= $price['PRINT_RATIO_PRICE'] ?: '-' ?></span>
    </div>
    <div class="product-table-cell">
        <? if ($actualItem['CAN_BUY']): ?>
            <div class="quantity" data-entity="quantity-block" data-product="<?= $actualItem['ID'] ?>">
                <div class="icon icon-minus" data-value="1" id="<?= $itemIds['QUANTITY_DOWN'] ?>"></div>
                <input type="number" name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>" value="0"
                       id="<?= $itemIds['QUANTITY'] ?>">
                <div class="icon icon-plus" data-value="1" id="<?= $itemIds['QUANTITY_UP'] ?>"></div>
            </div>
        <? else: ?>
            <div class="quantity">
                <div class="icon icon-minus" data-value="1"></div>
                <input type="number" name="" value="0">
                <div class="icon icon-plus" data-value="1"></div>
            </div>
        <? endif ?>
    </div>
</div>
