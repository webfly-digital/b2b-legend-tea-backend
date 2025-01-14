<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="splide news-slider">
    <? if ($arResult["ITEMS"]): ?>
        <div class="splide__arrows splide__arrows--ltr">
            <button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide"
                    aria-controls="splide01-track"></button>
            <button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide"
                    aria-controls="splide01-track"></button>
        </div>
        <div class="splide__track">
            <ul class="splide__list">
                <? foreach ($arResult["ITEMS"] as $arItem): ?>
                    <li class="splide__slide">
                        <div class="news-product-slide">
                            <div class="img-holder">
                                <? if ($arItem["PICTURE"]["src"]): ?>
                                    <img src="<?= $arItem["PICTURE"]["src"] ?>"
                                         alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                         title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                                <? endif ?>
                            </div>
                            <div class="news-product-slide--content">
                                <div class="info-block">
                                    <h5><? echo $arItem["NAME"] ?></h5>
                                    <p class="body"><? echo $arItem["PREVIEW_TEXT"]; ?></p>
                                </div>
                                <div class="price-block">
                                    <p class="subtitle">2 500₽</p>
                                    <p class="body">250г</p>
                                     <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>" class="button-full">
                                        <span>Купить</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
    <? endif ?>
</div>
