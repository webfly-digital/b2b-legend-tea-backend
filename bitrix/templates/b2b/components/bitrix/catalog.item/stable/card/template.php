<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

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
    <div class="product-table-cell">
        <div class="product-header">
            <div class="left">
                <div class="favourite " id="<?= $itemIds['FAVORITE_BTN'] ?>"></div>
            </div>
            <div class="right">
                <span class="detail-opener"
                      data-detail-btn="<?= $itemIds['DETAIL_BTN'] ?>"><?= $productTitle ?></span>
                <div class="information-icon detail-opener" data-detail-btn="<?= $itemIds['DETAIL_BTN'] ?>"></div>
                <div class="labels">
                    <div class="label grey-noborder"><?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?></div>
                    <? if ($item['LABEL']['ICON']): ?>
                        <div class="label <?= $item['LABEL']['CLASS'] ?>">
                            <div class="icon icon-<?= $item['LABEL']['ICON'] ?>"></div>
                            <span><?= $item['LABEL']['TEXT'] ?></span>
                        </div>
                    <? endif ?>
                    <? if (!$haveOffers): ?>
                        <div class="label grey"
                             id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>" <?= ($actualItem['CAN_BUY'] ? 'style="display: none;"' : '') ?>>
                            <div class="icon icon-truck"></div>
                            <span>Нет в наличии</span>
                        </div>
                    <? endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="product-table-cell">
        <span> <?= $item['PROPERTIES']['OPISANIE_ETIKETKA_DOP']['VALUE']?:'-' ?> </span>
    </div>
    <div class="product-table-cell spoiler">
        <div class="icon"></div>
    </div>
</div>
<div class="right">
    <?
    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP']))
        include 'offer.php';
    else
        include 'product.php'
    ?>
</div>
<?
include 'detail.php';
include 'js.php';
?>
