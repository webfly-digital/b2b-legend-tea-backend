<?
$showStock = true;
$stock = true;
if ($actualItem["CATALOG_QUANTITY"] < 1) {
    $stock = false;
    $popupInfo = '<div class="out-of-stock-content">Срок поставки до <span></span>' . $arParams['DELIVERY_TIME'] . '</span> ' . $day . '</div>';
}
?>

<?
//условие, если детальная карточка справа, и незаполнена цена, то не показываем функционал покупки
if ($detail_card && (empty($price['PRINT_RATIO_PRICE']) || $price['PRINT_RATIO_PRICE'] == '-')): ?>
<? else: ?>
    <div class="<?= $detail_card ? 'size' : "row" ?>">
        <? if ($primaryPropertyCode):
            $showStock = false;
            $no_value = $detail_card ? '' : "-";
            ?>
            <div class="product-table-cell <?= !$detail_card ? $stock ? 'in-stock' : 'out-of-stock' : '' ?>"
                 data-id="<?= !$stock ? $itemIds['POPUP_STOCK'] : '' ?>">
                <?= !$stock ? $popupInfo : '' ?>
                <span><?= $actualItem['PROPERTIES'][$primaryPropertyCode]['VALUE'] ?: $no_value ?></span>
            </div>
            <? if ($arParams['SEARCH'] == 'Y' && $no_value == '-'):?>
            <div class="product-table-cell"> -</div>
        <? endif ?>
        <? elseif ($arParams['SEARCH'] == 'Y' && !$detail_card == true):
            $showStock = false;?>
            <div class="product-table-cell <?= !$detail_card ? $stock ? 'in-stock' : 'out-of-stock' : '' ?>"
                 data-id="<?= !$stock ? $itemIds['POPUP_STOCK'] : '' ?>">
                <?= !$stock ? $popupInfo : '' ?> -</div>
            <div class="product-table-cell"> -</div>
        <? endif ?>
        <div class="product-table-cell <?= $showStock ? !$stock ? 'out-of-stock' : 'in-stock' : '' ?>"
             data-id="<?= $showStock ? !$stock ? $itemIds['POPUP_STOCK'] : '' : '' ?>" data-entity="price-block">
            <?= $showStock ? !$stock ? $popupInfo : '' : '' ?>
            <span id="<?= $itemIds['PRICE'] ?>"><?= $price['PRINT_RATIO_PRICE'] ?: '-' ?></span>
        </div>
        <div class="product-table-cell">
            <? if ($actualItem['CAN_BUY']): ?>
                <div class="quantity" data-entity="quantity-block"
                     data-product="<?= $actualItem['ID'] ?>">
                    <div class="icon icon-minus" data-value="1"
                        <?= $detail_card ? 'data-quantity-down="' . $itemIds['QUANTITY_DOWN'] . '"' : '' ?>
                         id="<?= $detail_card ? '' : $itemIds['QUANTITY_DOWN'] ?>"></div>
                    <input type="number" name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>" value="0"
                        <?= $detail_card ? 'data-quantity="' . $itemIds['QUANTITY'] . '"' : '' ?>
                           id="<?= $detail_card ? '' : $itemIds['QUANTITY'] ?>">
                    <div class="icon icon-plus" data-value="1"
                        <?= $detail_card ? 'data-quantity-up="' . $itemIds['QUANTITY_UP'] . '"' : '' ?>
                         id="<?= $detail_card ? '' : $itemIds['QUANTITY_UP'] ?>"></div>
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
<? endif ?>
