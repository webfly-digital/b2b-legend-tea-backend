<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 * @var array $arResult
 */
?>
<?php if (!empty($arResult)): ?>

    <?php
    foreach ($arResult as $arItem):
        if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
            continue;
        ?>
        <?php
        if ($arItem["PARAMS"]['nolink']):
            ?>
            <div class="item catalog-nav-opener">
                <div class="icon <?= $arItem["PARAMS"]['icon'] ?>"></div>
                <span><?= $arItem["TEXT"] ?></span>
            </div>
        <?php
        else:?>
            <a href="<?= $arItem["LINK"] ?>" id="elem-menu-mobile-<?= $arItem["PARAMS"]['icon'] ?>"
               class="item <?= strpos($arItem["LINK"], '/personal/order/make/') !== false ? 'item-cart' : '' ?> <?= $arItem["SELECTED"] ? 'active' : '' ?>">
                <div class="icon <?= $arItem["PARAMS"]['icon'] ?>">
                </div>
                <?php if (strpos($arItem["LINK"], '/personal/order/make/') !== false): ?>
                    <span id="cart-counter"></span>
                <?php endif; ?>
                <span><?= $arItem["TEXT"] ?></span>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    BX.ready(function () {
        // Функция для обновления счётчика товаров
        function updateCartCounter() {
            BX.ajax({
                url: '/local/tools/basket_count.php',
                method: 'POST',
                dataType: 'json',
                data: {'sessid': BX.bitrix_sessid()},
                onsuccess: function (response) {
                    if (response && response.NUM_PRODUCTS !== undefined) {
                        // var counter = document.getElementById('cart-counter');
                        // counter.innerText = response.NUM_PRODUCTS > 0 ? response.NUM_PRODUCTS : '';

                    }
                },
                onfailure: function (error) {
                }
            });
        }

        // Подписываемся на событие изменения корзины
        BX.addCustomEvent('OnBasketChange', function () {
            updateCartCounter();
        });

        // Первый запуск для отображения текущего количества товаров при загрузке страницы
        updateCartCounter();
    });
</script>

<!--<script>-->
<!--    BX.ready(function () {-->
<!--        BX.ajax({-->
<!--            url: '/local/tools/basket_count.php',-->
<!--            method: 'POST',-->
<!--            dataType: 'json',-->
<!--            data: {'sessid': BX.bitrix_sessid()},-->
<!--            onsuccess: function (response) {-->
<!--                console.log('AJAX response received:', response);-->
<!--                if (response && response.NUM_PRODUCTS !== undefined) {-->
<!--                    var counter = document.getElementById('cart-counter');-->
<!--                    counter.innerText = response.NUM_PRODUCTS > 0 ? response.NUM_PRODUCTS : '';-->
<!--                }-->
<!--            },-->
<!--            onfailure: function (error) {-->
<!--                console.error('AJAX request failed:', error);-->
<!--            }-->
<!--        });-->
<!--    });-->
<!--</script>-->