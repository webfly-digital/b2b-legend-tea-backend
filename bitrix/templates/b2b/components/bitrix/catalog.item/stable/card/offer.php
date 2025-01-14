<?php
if ($arResult['ITEM']['SEPARATE_OFFERS']):
    foreach ($arResult['ITEM']['SEPARATE_OFFERS'] as $offer):
        $primary = $offer['PROPERTIES']['PRIMARY'];
        $secondary = $offer['PROPERTIES']['SECONDARY'];
        ?>
        <div class="row" id="<?= $itemIds['PROP_DIV'] ?>_offer_<?= $offer['ITEM']['ID'] ?>">
            <!--Свойства sku-->
                <div class="product-table-cell" data-treevalue="<?= $primary['ID'] ?>_<?= $primary['VALUE']['ID'] ?>"
                     data-onevalue="<?= $primary['VALUE']['ID'] ?>">
                    <span><?= $primary['VALUE']['NAME'] ?></span>
                </div>
                <?
                if ($secondary):?>
                    <div class="product-table-cell">
                        <? if ($secondary['VALUE']): ?>
                            <div class="select" data-entity="sku-line-block">
                                <select name="" class="sku-change">
                                    <?
                                    foreach ($secondary['VALUE'] as $valId):
                                        if (!isset($item['SKU_TREE_VALUES'][$arParams['SKU_PROPS']['POMOL']['ID']][$valId]))
                                            continue; ?>
                                        <option data-treevalue="<?= $secondary['ID'] ?>_<?= $valId ?>"
                                                data-onevalue="<?= $valId ?>"
                                                value="<?= $valId ?>"><?= $arParams['SKU_PROPS']['POMOL']['VALUES'][$valId]['NAME'] ?></option>
                                    <? endforeach ?>
                                </select>
                                <div class="icon"></div>
                            </div>
                        <? else: ?>
                            -
                        <? endif ?>
                    </div>
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
                <span id="<?= $itemIds['PRICE'] ?>_offer_<?= $offer['ITEM']['ID'] ?>"><?= $price['PRINT_RATIO_PRICE']; ?></span>
            </div>
            <!--Количество-->
            <div class="product-table-cell" data-entity="quantity-block" data-product="<?= $offer['ITEM']['ID'] ?>">
                <div class="quantity" >
                    <div class="icon icon-minus" data-value="1"
                         id="<?= $itemIds['QUANTITY_DOWN'] ?>_offer_<?= $offer['ITEM']['ID'] ?>"></div>
                    <input type="number" name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>" value="0"
                           id="<?= $itemIds['QUANTITY'] ?>_offer_<?= $offer['ITEM']['ID'] ?>">
                    <div class="icon icon-plus" data-value="1"
                         id="<?= $itemIds['QUANTITY_UP'] ?>_offer_<?= $offer['ITEM']['ID'] ?>"></div>
                </div>
            </div>
        </div>
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
