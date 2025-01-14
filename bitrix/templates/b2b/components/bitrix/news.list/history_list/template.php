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
<? if (!empty($arResult["ITEMS"])): ?>
    <section>
        <div class="splide stories-slider">
            <div class="splide__arrows splide__arrows--ltr">
                <button class="splide__arrow splide__arrow--prev" type="button" aria-label="Previous slide"
                        aria-controls="splide01-track"></button>
                <button class="splide__arrow splide__arrow--next" type="button" aria-label="Next slide"
                        aria-controls="splide01-track"></button>
            </div>
            <div class="splide__track">
                <ul class="splide__list">
                    <? foreach ($arResult["ITEMS"] as $arItem):  ?>
                        <li class="splide__slide">
                            <a href='<?= $arItem['PROPERTIES']['LINK']["VALUE"] ?>' class="stories-item" target="_blank">
                                <div class="img-holder">
                                    <img src="<?= !empty($arItem["PREVIEW_PICTURE"]) ? $arItem["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH . '/assets/static/img/stories-2.png' ?>"
                                         alt="">
                                </div>
                                <h5><?= $arItem["NAME"] ?></h5>
                                <p class="body"><?= $arItem["PREVIEW_TEXT"] ?></p>
                            </a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
<? endif; ?>
