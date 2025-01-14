<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$this->IncludeLangFile('template.php');
$cartId = $arParams['cartId'];
$addToBasketUrl = SITE_TEMPLATE_PATH . '/ajax/basket.php';
$searchUserUrl = SITE_TEMPLATE_PATH . '/ajax/searchUser.php';


global $USER;
$modeAdmin = false;
$session = \Bitrix\Main\Application::getInstance()->getSession();
if ($session->has('mode') && $session['mode'] == '21232f297a57a5a743894a0e4a801fc3' || $USER->isAdmin() || in_array(24, $USER->GetUserGroupArray())) $modeAdmin = true;
?>
<div class="cart-header">
    <h3>Ваш заказ</h3>
    <? if ($arResult['GROUPED_PRODUCTS']): ?>
        <div class="small-text clear" id="deleteAll">Очистить</div>
    <? endif ?>
</div>
<div class="cart-body">
    <? if ($arResult['GROUPED_PRODUCTS']): ?>
        <div class="prices">
            <? foreach ($arResult['GROUPED_PRODUCTS'] as $group): ?>
                <div class="item">
                    <div class="name"><?= $group['NAME'] ?></div>
                    <div class="price"><?= $group['SUM_FORMAT'] ?></div>
                </div>
            <? endforeach ?>
            <? if (!empty($arResult["DISCONT_PRICE_FORMAT"])): ?>
                <div class="item">
                    <div class="name">Выгода</div>
                    <div class="price"><?= $arResult["DISCONT_PRICE_FORMAT"] ?></div>
                </div>
            <? endif; ?>
        </div>
        <? if (!empty($arResult["SALE"])): ?>
            <div class="note">
                <?= $arResult["SALE"]['TEXT'] ?><br>
                <ul class="ml-2">
                    <li><?= $arResult["SALE"]['MONTIS']['TEXT'] ?></li>
                    <li><?= $arResult["SALE"]['LEGENDA_TEA']['TEXT'] ?></li>
                </ul>
                <a href="/rules/#83809" class="text-red"><br>Подробнее о скидках</a>
            </div>
        <? endif; ?>
    <? else: ?>
        <div class="subtitle text-grey3">Ваша корзина пуста</div>
        <div class="note">
            <span>Как получить скидку?</span>
            <br>
            <span>Добавьте товаров на сумму <br> 15 000 ₽ и получите скидку:</span>
            <ul class="ml-2 mb-1">
                <li>7% от цены на кофе «Montis»,</li>
                <li>3% от цены на чай бренда «Легенда чая».</li>
            </ul>
            <a href="/rules/#83809" class="text-red"><br>Подробнее о скидках</a>
        </div>
    <? endif ?>
    <? if ($modeAdmin) {
        ?>
        <div class="user-choose mb-4">
            <div class="subtitle">Поиск пользователя для заказа</div>
            <input value="" id="search_user" placeholder="Введите ФИО">
            <div id="blockResponse" class="listUsers">
            </div>
            <div class="small-text">Введите ФИО, название компании, номер телефона или email клиента, на аккаунт которого будет оформлен заказ</div>
            <? if (!$USER->IsAdmin() && !in_array(24, $USER->GetUserGroupArray())): ?>
                <div class="small-text mt-3">Вы авторизованы
                под <?= $USER->GetFullName() . ' (' . $USER->GetLogin() . ')' ?>  <a href="/?logout=yes">Выйти<a> </div><?endif; ?>
        </div>
        <?
    }
    ?>
</div>
<div class="cart-footer">
    <div class="total">
        <span>итого</span>
        <span><?= $arResult['TOTAL_PRICE_FORMAT'] ?></span>
    </div>
    <? if ($arResult["GROUPED_PRODUCTS"]): ?>
        <a href="<?= $arParams["PATH_TO_ORDER"] ?>" class="button-full">
            <span>Оформить заказ</span>
            <div class="icon icon-arrow-right"></div>
        </a>
    <? else: ?>
        <div class="button-full disabled">
            <span>Оформить заказ</span>
            <div class="icon icon-arrow-right"></div>
        </div>
    <? endif ?>
</div>
<script>
    BasketToCatalogUpdater.init(
        {
            items: <?=CUtil::PhpToJSObject($arResult['CATALOG_BASKET'] ?: [], false, true)?>,
            basketUrl: '<?=$addToBasketUrl?>',
            searchUserUrl: '<?=$searchUserUrl?>'
        }
    );
</script>
