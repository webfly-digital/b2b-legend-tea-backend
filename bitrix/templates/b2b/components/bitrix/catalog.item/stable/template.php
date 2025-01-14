<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

if (isset($arResult['ITEM'])) {
    $item = $arResult['ITEM'];
    $haveOffers = !empty($item['OFFERS']);

    $primaryPropertyCode = $item['COLUMNS'][0]['CODE'];

    $goodItem = $haveOffers && $primaryPropertyCode || !$haveOffers;

    if ($goodItem) {

        $areaId = $arResult['AREA_ID'];
        $itemIds = array(
            'ID' => $areaId,
            'PICT' => $areaId . '_pict',
            'SECOND_PICT' => $areaId . '_secondpict',
            'PICT_SLIDER' => $areaId . '_pict_slider',
            'STICKER_ID' => $areaId . '_sticker',
            'SECOND_STICKER_ID' => $areaId . '_secondsticker',
            'QUANTITY' => $areaId . '_quantity',
            'QUANTITY_DOWN' => $areaId . '_quant_down',
            'QUANTITY_UP' => $areaId . '_quant_up',
            'QUANTITY_MEASURE' => $areaId . '_quant_measure',
            'QUANTITY_LIMIT' => $areaId . '_quant_limit',
            'BUY_LINK' => $areaId . '_buy_link',
            'BASKET_ACTIONS' => $areaId . '_basket_actions',
            'NOT_AVAILABLE_MESS' => $areaId . '_not_avail',
            'SUBSCRIBE_LINK' => $areaId . '_subscribe',
            'SUBSCRIBE_LINK_HIDDEN' => $areaId . '_subscribe-hidden',
            'COMPARE_LINK' => $areaId . '_compare_link',
            'PRICE' => $areaId . '_price',
            'PRICE_OLD' => $areaId . '_price_old',
            'PRICE_TOTAL' => $areaId . '_price_total',
            'DSC_PERC' => $areaId . '_dsc_perc',
            'SECOND_DSC_PERC' => $areaId . '_second_dsc_perc',
            'PROP_DIV' => $areaId . '_sku_tree',
            'PROP' => $areaId . '_prop_',
            'DISPLAY_PROP_DIV' => $areaId . '_sku_prop',
            'BASKET_PROP_DIV' => $areaId . '_basket_prop',
            'DETAIL_BTN' => $areaId . '_detail_slider',
            'DETAIL_BLOCK' => $areaId . '_detail_slider-block',
            'FAVORITE_BTN' => $areaId . '_favorite',
        );
        $obName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);

        $productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
            ? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
            : $item['NAME'];

        $imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
            ? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
            : $item['NAME'];

        $skuProps = array();

        if ($haveOffers) {
            $actualItem = isset($item['OFFERS'][$item['OFFERS_SELECTED']])
                ? $item['OFFERS'][$item['OFFERS_SELECTED']]
                : reset($item['OFFERS']);
        } else {
            $actualItem = $item;
        }

        if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers) {
            $price = $item['ITEM_START_PRICE'];
            $minOffer = $item['OFFERS'][$item['ITEM_START_PRICE_SELECTED']];
            $measureRatio = $minOffer['ITEM_MEASURE_RATIOS'][$minOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
        } else {
            $price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
            $measureRatio = $price['MIN_QUANTITY'];
        }
        $price['PRINT_RATIO_PRICE'] = $price['PRINT_RATIO_PRICE'] ? \SaleFormatCurrency(
                $price['RATIO_PRICE'],
                'RUB',
                true
            ) . ' ₽' : '-';

        $showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($item['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);
        $itemHasDetailUrl = isset($item['DETAIL_PAGE_URL']) && $item['DETAIL_PAGE_URL'] != '';
        ?>

        <div class="product-table-row <?= (!$actualItem['CAN_BUY'] && !$haveOffers) ? 'disabled' : '' ?>"
             id="<?= $areaId ?>"
             data-entity="item">
            <?
            $documentRoot = Main\Application::getDocumentRoot();
            $templatePath = mb_strtolower($arResult['TYPE']) . '/template.php';
            $file = new Main\IO\File($documentRoot . $templateFolder . '/' . $templatePath);

            if ($file->isExists()) {
                include($file->getPath());
            }
            ?>
            <?
            if ($item['SEPARATE_OFFERS']): ?>
                <?
            foreach ($item['SEPARATE_OFFERS'] as $activeOffer): ?>
                <script>
                    var <?=$obName . '_offer_' . $activeOffer['ITEM']['ID']?> =
                    new JCCatalogItem(<?=CUtil::PhpToJSObject($jsParams[$activeOffer['ITEM']['ID']], false, true)?>);
                </script>
            <? endforeach ?>
                <script>
                    var <?=$obName . 'additional_offer'?> =
                    new JCCatalogOneItem(<?=CUtil::PhpToJSObject($additionalJsParams, false, true)?>);
                </script>
            <? else: ?>
                <script>
                    var <?=$obName?> =
                    new JCCatalogItem(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
                    var <?=$obName. 'additional'?> =
                    new JCCatalogOneItem(<?=CUtil::PhpToJSObject($additionalJsParams, false, true)?>);
                </script>
            <? endif ?>
        </div>
        <?
        unset($item, $actualItem, $minOffer, $itemIds, $jsParams);
    }
}
