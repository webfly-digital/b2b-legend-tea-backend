<div id="<?= $itemIds['DETAIL_BLOCK'] ?>" style="display:none;">
    <div class="side-buttons">
        <div class="side-button close">
            <div class="icon icon-cross "></div>
            <p>Закрыть</p>
        </div>
    </div>
    <!--    <div class="header">-->
    <!--        --><? //if (!$haveOffers && !$actualItem['CAN_BUY'] && $showSubscribe):?>
    <!--            --><? //
    //            $APPLICATION->IncludeComponent(
    //                'bitrix:catalog.product.subscribe',
    //                '',
    //                array(
    //                    'PRODUCT_ID' => $actualItem['ID'],
    //                    'BUTTON_ID' =>  $itemIds['SUBSCRIBE_LINK'],
    //                    'BUTTON_CLASS' => 'link',
    //                    'DEFAULT_DISPLAY' => true,
    //                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
    //                ),
    //                false,
    //                array('HIDE_ICONS' => 'Y')
    //            ); ?>
    <!--        --><? //endif?>
    <!--        <div class="icon icon-cross close"></div>-->
    <!--    </div>-->
    <div class="body">
        <div class="product-slide-header">


            <div class="title-field">
                <div class="title-field-inline">
                    <h3><?= $productTitle ?></h3>
                    <div class="labels-holder scroll-available">
                        <div class="scroll-to-left"></div>
                        <div class="scroll-to-right"></div>
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
                                     id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>_duplicate" <?= ($actualItem['CAN_BUY'] ? 'style="display: none;"' : '') ?>>
                                    <div class="icon icon-truck"></div>
                                    <span>Нет в наличии</span>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                </div>
                <div class="subtitle"><?= $item['PROPERTIES']["OPISANIE_ETIKETKA_DOP"]['VALUE'] ?></div>
            </div>

            <div class="slider">
                <? if ($item['PICTURES']): ?>
                    <div class="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <? foreach ($item['PICTURES'] as $key => $picture): ?>
                                    <li class="splide__slide">
                                        <a class="slide glightbox" href="<?= $item['PICTURES_ORIGIN'][$key] ?>"
                                           data-gallery="gallery-<?= $item['ID'] ?>">
                                            <div class="content">
                                                <img src="<?= $picture['src'] ?>"
                                                     alt="<?= $productTitle ?>"
                                                     title="<?= $productTitle ?>">
                                            </div>
                                        </a>
                                    </li>
                                <? endforeach ?>
                            </ul>
                        </div>
                    </div>
                <? else: ?>
                    <div class="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <li class="splide__slide">
                                    <div class="slide">
                                        <div class="content">
                                            <img src="/bitrix/templates/b2b/assets/static/img/img-holder2.png">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                <? endif ?>
            </div>
            <div class="subslider">
                <div class="favourite detail-favorite" id="<?= $itemIds['FAVORITE_BTN'] ?>_detail"></div>
                <? if ($arParams["SECTION_MAIN"]['ID'] == ID_SECTION_CHAY) include 'detail_info_chay.php'; ?>

                <? if ($arParams["SECTION_MAIN"]['ID'] == ID_SECTION_MONTIS) include 'detail_info_coffee.php'; ?>

            </div>
        </div>
        <div class="product-slide-sizes <?= (!$actualItem['CAN_BUY'] && !$haveOffers) ? 'disabled' : '' ?>">
            <?
            $detail_card = true;
            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP'])) include 'offer.php';
            else include 'product.php'; ?>
        </div>
        <?
        $showText = $item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] || $item['DETAIL_TEXT'] ? true : false;
        $generatePdf = $arParams['GENERATE_PDF'] ? true : false;
        ?>
        <div class="product-slide-description">
            <? if ($showText || $generatePdf): ?>
                <nav>
                    <? if ($showText): ?>
                        <label>
                            <input type="radio" name="lol" checked="">
                            <p>Описание</p>
                        </label>
                    <? endif ?>
                    <? if ($generatePdf): ?>
                        <label>
                            <input type="radio"
                                   name="lol" <?= $item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] || $item['DETAIL_TEXT'] == false ? ' checked=""' : '' ?>>
                            <p>Файлы для скачивания</p>
                        </label>
                    <? endif ?>

                    <!--                <label>-->
                    <!--                    <input type="radio" name="lol">-->
                    <!--                    <p>Рекомендуемые цены</p>-->
                    <!--                </label>-->
                </nav>
                <div class="content">
                    <? if ($showText): ?>
                        <div class="content_block">
                            <?= $item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] ? htmlspecialcharsBack($item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE']) : $item['DETAIL_TEXT'] ?>
                        </div>
                    <? endif ?>
                    <? if ($generatePdf): ?>
                        <div class="content_block">
                            <nav class="links">
                                <div class="download" data-generate-pdf-btn="<?= $itemIds['DOWNLOAD_PDF_BTN'] ?>">
                                    Маркетинговые файлы
                                </div>
                                <?
                                $resCompany = \Webfly\Helper\Helper::getProfileLogo($arParams["USER_ID"]);
                                if (!empty($resCompany)):?>
                                    <? foreach ($resCompany as $company): ?>
                                        <div class="download"
                                             data-generate-pdf-btn="<?= $itemIds['DOWNLOAD_PDF_BTN'] ?>"
                                             data-generate-pdf-profile-id="<?= $company['ID'] ?>">
                                            Маркетинговые файлы с логотипом «<?= $company['NAME'] ?>»
                                        </div>
                                    <? endforeach; ?>
                                <? endif ?>
                            </nav>
                        </div>
                    <? endif ?>
                    <!--                <div class="content_block"></div>-->
                </div>
            <? endif; ?>
        </div>
    </div>
</div>
