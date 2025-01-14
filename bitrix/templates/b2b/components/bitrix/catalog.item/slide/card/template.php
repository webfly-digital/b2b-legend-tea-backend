<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

<div class="news-product-slide">
    <div class="img-holder">
        <img src="<?= $item["DETAIL_PICTURE"]['SRC'] ?: SITE_TEMPLATE_PATH . '/assets/static/img/img-holder2.png' ?>"
             alt="">
    </div>
    <div class="news-product-slide--content">
        <div class="info-block">
            <h5><?= $item['NAME'] ?></h5>
            <p class="body"> <?= $item['PROPERTIES']['OPISANIE_ETIKETKA_DOP']['VALUE'] ?: '-' ?></p>
        </div>
        <?
        $dayDeclension = new Bitrix\Main\Grid\Declension('день', 'дня', 'дней');
        $day = $dayDeclension->get($arParams['DELIVERY_TIME']);
        if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP']))
            include 'offer.php';
        else
            include 'product.php'
        ?>
        <? if (!$haveOffers): ?>
            <div class="label grey"
                 id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>" <?= ($actualItem['CAN_BUY'] ? 'style="display: none;"' : '') ?>>
                <div class="icon icon-truck"></div>
                <span>Нет в наличии</span>
            </div>
        <? endif ?>
    </div>
</div>


