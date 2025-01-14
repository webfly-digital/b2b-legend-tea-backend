<?php

if ($arResult['ITEM']['SEPARATE_OFFERS']):
    foreach ($arResult['ITEM']['SEPARATE_OFFERS'] as $offer):
        $primary = $offer['PROPERTIES']['PRIMARY'];
        $secondary = $offer['PROPERTIES']['SECONDARY'];

        $stock = true;
        if ($offer['ITEM']["MAX_QUANTITY"] < 1) {
            $stock = false;
            $popupInfo = '<div class="out-of-stock-content">Срок поставки до <span></span>' . $arParams['DELIVERY_TIME'] . '</span> ' . $day . '</div>';
        }
        ?>
        <?
//условие, если детальная карточка справа, если свойство не заполнена и незаполнена цена, то не показываем функционал покупки
        if ($detail_card && (empty($offer['ITEM']["ITEM_PRICES"][$offer['ITEM']['ITEM_PRICE_SELECTED']]) || $offer['ITEM']["ITEM_PRICES"][$offer['ITEM']['ITEM_PRICE_SELECTED']] == '-')): ?>
        <? else: ?>
            <div class="<?= $detail_card ? 'size' : "row" ?>" <?= $detail_card ? 'data-offers="' . $itemIds['PROP_DIV'] . '_offer_' . $offer['ITEM']['ID'] . '"' : '' ?>
                 id="<?= $detail_card ? '' : $itemIds['PROP_DIV'] . '_offer_' . $offer['ITEM']['ID'] ?>">
                <!--Свойства sku-->
                <div class="product-table-cell <?= !$detail_card ? $stock ? 'in-stock' : 'out-of-stock' : '' ?>"
                     data-id="<?= !$stock ? $itemIds['POPUP_STOCK'] : '' ?>"
                     data-treevalue="<?= $primary['ID'] ?>_<?= $primary['VALUE']['ID'] ?>"
                     data-onevalue="<?= $primary['VALUE']['ID'] ?>">
                    <?= !$stock ? $popupInfo : '' ?>
                    <span><?= $primary['VALUE']['NAME'] ?></span>
                </div>
                <? if ($secondary): ?>
                    <div class="product-table-cell">
                        <? if ($secondary['VALUE']): ?>
                            <div class="select" data-entity="sku-line-block">
                                <select name=""
                                        class="sku-change choices" <?='data-choices="' . 'id_' . $offer['ITEM']['ID'] . '_offer_' . $secondary['ID'] . '"'  ?>>
                                    <? foreach ($secondary['VALUE'] as $valId):
                                        if (!isset($item['SKU_TREE_VALUES'][$arParams['SKU_PROPS']['POMOL']['ID']][$valId]))
                                            continue; ?>
                                        <option data-treevalue="<?= $secondary['ID'] ?>_<?= $valId ?>"
                                                data-custom-properties="<?= $secondary['ID'] ?>_<?= $valId ?>"
                                                data-onevalue="<?= $valId ?>"
                                                value="<?= $valId ?>"><?= $arParams['SKU_PROPS']['POMOL']['VALUES'][$valId]['NAME'] ?></option>
                                    <? endforeach ?>
                                </select>
                            </div>
                        <?  else: ?>
                            -
                        <? endif ?>
                    </div>
                <? elseif ($arParams['SEARCH'] == 'Y' && !$detail_card == true): ?>
                    <div class="product-table-cell"> -</div>
                <? endif ?>
                <!--Цена-->
                <div class="product-table-cell" data-entity="price-block">
                    <?
                    $price = $offer['ITEM']["ITEM_PRICES"][$offer['ITEM']['ITEM_PRICE_SELECTED']];
                    $price['PRINT_RATIO_PRICE'] = $price['PRINT_RATIO_PRICE'] ? \SaleFormatCurrency(
                            $price['RATIO_PRICE'],
                            'RUB',
                            true
                        ) . ' ₽' : '-';
                    ?>
                    <span <?= $detail_card ? 'data-price="' . $itemIds['QUANTITY_UP'] . '_offer_' . $offer['ITEM']['ID'] . '"' : '' ?>  id="<?= $detail_card ? '' : $itemIds['PRICE'] ?>_offer_<?= $offer['ITEM']['ID'] ?>"><?= $price['PRINT_RATIO_PRICE']; ?></span>
                </div>
                <!--Количество-->
                <div class="product-table-cell" data-entity="quantity-block" data-product="<?= $offer['ITEM']['ID'] ?>">
                    <div class="quantity">
                        <div class="icon icon-minus"
                             data-value="1" <?= $detail_card ? 'data-quantity-down="' . $itemIds['QUANTITY_DOWN'] . '_offer_' . $offer['ITEM']['ID'] . '"' : '' ?>
                             id="<?= $detail_card ? '' : $itemIds['QUANTITY_DOWN'] . '_offer_' . $offer['ITEM']['ID'] ?>"></div>
                        <input type="number" name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
                               value="0" <?= $detail_card ? 'data-quantity="' . $itemIds['QUANTITY'] . '_offer_' . $offer['ITEM']['ID'] . '"' : '' ?>
                               id="<?= $detail_card ? '' : $itemIds['QUANTITY'] . '_offer_' . $offer['ITEM']['ID'] ?>">
                        <div class="icon icon-plus"
                             data-value="1" <?= $detail_card ? 'data-quantity-up="' . $itemIds['QUANTITY_UP'] . '_offer_' . $offer['ITEM']['ID'] . '"' : '' ?>
                             id="<?= $detail_card ? '' : $itemIds['QUANTITY_UP'] . '_offer_' . $offer['ITEM']['ID'] ?>"></div>
                    </div>
                </div>
            </div>
        <? endif ?>
    <?
    endforeach;
endif;

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
