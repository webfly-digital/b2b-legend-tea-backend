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
            <div class="slider">
                <? if ($item['PICTURES']): ?>
                    <div class="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <? foreach ($item['PICTURES'] as $picture): ?>
                                    <li class="splide__slide">
                                        <div class="slide">
                                            <div class="content">
                                                <img src="<?= $picture['src'] ?>" alt="<?= $productTitle ?>"
                                                     title="<?= $productTitle ?>">
                                            </div>
                                        </div>
                                    </li>
                                <? endforeach ?>
                            </ul>
                        </div>
                    </div>
                <?else:?>
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
                <!--                <p class="my-1">Вид кофе</p>-->
                <div class="favourite detail-favorite" id="<?= $itemIds['FAVORITE_BTN'] ?>_detail"></div>
                <h3><?= $productTitle ?></h3>
                <div class="subtitle"><?= $item['PREVIEW_TEXT'] ?></div>
                <div class="characteristics">
                    <? if (!empty($item['DISPLAY_PROPERTIES'])): ?>
                        <h4 class="title">Характеристики:</h4>
                        <div class="text-items">
                            <? foreach ($item['DISPLAY_PROPERTIES'] as $displayProperty): ?>
                                <div class="item">
                                    <div class="name"><?= $displayProperty['NAME'] ?>:</div>
                                    <div class="value"><?= (is_array($displayProperty['DISPLAY_VALUE'])
                                            ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                            : $displayProperty['DISPLAY_VALUE']) ?></div>
                                </div>
                            <? endforeach ?>
                        </div>
                    <? endif ?>
                    <? if ($item['RANGES']): ?>
                        <div class="progress-items">
                            <? foreach ($item['RANGES'] as $rangeItem): ?>
                                <div class="item">
                                    <div class="progress" style="--val: <?= $rangeItem['VALUE'] ?>%"></div>
                                    <div class="text"><?= $rangeItem['NAME'] ?></div>
                                </div>
                            <? endforeach ?>
                        </div>
                    <? endif ?>
                </div>
            </div>
        </div>
        <!--        <div class="product-slide-sizes">-->
        <!--            <div class="size">-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>357 г</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>1 700 ₽</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <div class="quantity">-->
        <!--                        <div class="icon icon-minus" data-value="-1"></div>-->
        <!--                        <input type="number" placeholder="0" name="" id="" min="0" max="9999">-->
        <!--                        <div class="icon icon-plus" data-value="1"></div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="size">-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>357 г</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>1 700 ₽</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <div class="quantity">-->
        <!--                        <div class="icon icon-minus" data-value="-1"></div>-->
        <!--                        <input type="number" placeholder="0" name="" id="" min="0" max="9999">-->
        <!--                        <div class="icon icon-plus" data-value="1"></div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="size">-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>357 г</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>1 700 ₽</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <div class="quantity">-->
        <!--                        <div class="icon icon-minus" data-value="-1"></div>-->
        <!--                        <input type="number" placeholder="0" name="" id="" min="0" max="9999">-->
        <!--                        <div class="icon icon-plus" data-value="1"></div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="size">-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>357 г</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <span>1 700 ₽</span>-->
        <!--                </div>-->
        <!--                <div class="product-table-cell">-->
        <!--                    <div class="quantity">-->
        <!--                        <div class="icon icon-minus" data-value="-1"></div>-->
        <!--                        <input type="number" placeholder="0" name="" id="" min="0" max="9999">-->
        <!--                        <div class="icon icon-plus" data-value="1"></div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="product-slide-description">
            <nav>
                <? if ($item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] || $item['DETAIL_TEXT']): ?>
                    <label>
                        <input type="radio" name="lol" checked="">
                        <p>Описание</p>
                    </label>
                <? endif ?>
                <label>
                    <input type="radio" name="lol" <?=$item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] || $item['DETAIL_TEXT'] == false?' checked=""':''?>>
                    <p>Файлы для скачивания</p>
                </label>

                <!--                <label>-->
                <!--                    <input type="radio" name="lol">-->
                <!--                    <p>Рекомендуемые цены</p>-->
                <!--                </label>-->
            </nav>
            <div class="content">
                <? if ($item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] || $item['DETAIL_TEXT']): ?>
                    <div class="content_block">
                        <?= $item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] ? htmlspecialcharsBack($item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE']) : $item['DETAIL_TEXT'] ?>
                    </div>
                <? endif ?>
                <div class="content_block">
                    <nav class="links">
                        <a href="" class="download" download="">Маркетинговые файлы</a>
                    </nav>
                </div>
                <!--                <div class="content_block"></div>-->
            </div>
        </div>
    </div>
</div>
