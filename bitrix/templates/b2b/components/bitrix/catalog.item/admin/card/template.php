<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var string $discountPositionClass
 * @var string $labelPositionClass
 * @var CatalogSectionComponent $component
 */
?>

<div class="left">
    <?php if ($arParams['VIEW'] == 'image'): ?>
        <div class="img-holder detail-opener" data-detail-btn="<?= $itemIds['DETAIL_BTN'] ?>">
            <img src="<?= $item["DETAIL_PICTURE"]['SRC'] ?: SITE_TEMPLATE_PATH . '/assets/static/img/img-holder2.png' ?>"
                 alt="">
        </div>
    <?php endif; ?>
    <div class="product-table-cell">
        <div class="product-header">
            <div class="left">
                <div class="favourite " id="<?= $itemIds['FAVORITE_BTN'] ?>"></div>
            </div>
            <div class="right">
                    <span class="detail-opener"
                          data-detail-btn="<?= $itemIds['DETAIL_BTN'] ?>"><?= $productTitle ?></span>
                <div class="information-icon detail-opener" data-detail-btn="<?= $itemIds['DETAIL_BTN'] ?>"></div>
                <span> <?= $item['PROPERTIES']['OPISANIE_ETIKETKA_DOP']['VALUE'] ?: '-' ?> </span>
                <div class="labels">
                    <div class="label grey-noborder"><?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?></div>
                    <?php if ($item['LABEL']['ICON']): ?>
                        <div class="label <?= $item['LABEL']['CLASS'] ?>">
                            <div class="icon icon-<?= $item['LABEL']['ICON'] ?>"></div>
                            <span><?= $item['LABEL']['TEXT'] ?></span>
                        </div>
                    <?php endif ?>
                    <?php if (!$haveOffers): ?>
                        <div class="label grey"
                             id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>" <?= ($actualItem['CAN_BUY'] ? 'style="display: none;"' : '') ?>>
                            <div class="icon icon-truck"></div>
                            <span>Нет в наличии</span>
                        </div>
                    <?php endif ?>
                </div>
                <?php if ($arParams['GENERATE_PDF']): ?>
                    <div class="content_block">
                        <nav class="links">
                            <div class="download" data-generate-pdf-btn="<?= $itemIds['DOWNLOAD_PDF_BTN'] ?>">
                                Маркетинговые файлы
                            </div>
                        </nav>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="product-table-cell spoiler">
        <div class="icon"></div>
    </div>
</div>
<div class="right">
    <?php
    $dayDeclension = new Bitrix\Main\Grid\Declension('день', 'дня', 'дней');
    $day = $dayDeclension->get($arParams['DELIVERY_TIME']);
    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP']))
        include 'offer.php';
    else
        include 'product.php'
    ?>
</div>
<?php
include 'detail.php';
include 'js.php';
?>