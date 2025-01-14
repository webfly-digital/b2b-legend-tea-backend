<div id="<?= $itemIds['PROP_DIV'] ?>">
    <? foreach ($arParams['SKU_PROPS'] as $skuProperty):
        $propertyId = $skuProperty['ID'];
        $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
        if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
            continue;
        ?>
        <? //= $skuProperty['NAME']
        ?>
        <select class="choices sku-change">
            <? foreach ($skuProperty['VALUES'] as $value):
                if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']])) continue;
                $value['NAME'] = htmlspecialcharsbx($value['NAME']); ?>
                <option value="<?= $propertyId ?>_<?= $value['ID'] ?>"
                        data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                        data-onevalue="<?= $value['ID'] ?>">
                    <?= $value['NAME'] ?>
                </option>
            <? endforeach; ?>
        </select>
    <? endforeach; ?>
</div>
<div class="price-block">
    <div class="subtitle" data-entity="price-block">
        <span id="<?= $itemIds['PRICE'] ?>"><?= $price['PRINT_RATIO_PRICE'] ?: '-' ?></span>
    </div>

    <? if ($actualItem['CAN_BUY']): ?>
        <div data-entity="button-block" id="<?= $itemIds['BUY_LINK'] ?>">
            <div class="button-full" data-in-basket="N">
                <span>Купить</span>
            </div>
            <div style="display: none" class="button-variable bordered" data-in-basket="Y">
                <span>В корзине</span>
            </div>
        </div>
        <div style="display: none" class="quantity" data-entity="quantity-block">
            <div class="icon icon-minus" data-value="1" id="<?= $itemIds['QUANTITY_DOWN'] ?>"></div>
            <input type="number" name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>" value="0"
                   id="<?= $itemIds['QUANTITY'] ?>">
            <div class="icon icon-plus" data-value="1" id="<?= $itemIds['QUANTITY_UP'] ?>"></div>
        </div>
    <? endif ?>
</div>

<?
foreach ($arParams['SKU_PROPS'] as $skuProperty) {
    if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
        continue;

    $skuProps[] = array(
        'ID' => $skuProperty['ID'],
        'SHOW_MODE' => $skuProperty['SHOW_MODE'],
        'VALUES' => $skuProperty['VALUES'],
        'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
    );
}
unset($skuProperty, $value);
if ($item['OFFERS_PROPS_DISPLAY']) {
    foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer) {
        $strProps = '';

        if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
            foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty) {
                $strProps .= '<dt>' . $displayProperty['NAME'] . '</dt><dd>'
                    . (is_array($displayProperty['VALUE'])
                        ? implode(' / ', $displayProperty['VALUE'])
                        : $displayProperty['VALUE'])
                    . '</dd>';
            }
        }

        $item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
    }
    unset($jsOffer, $strProps);
}
?>
