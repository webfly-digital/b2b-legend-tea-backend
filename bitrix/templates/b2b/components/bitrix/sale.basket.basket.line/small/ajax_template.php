<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];?>
<div class="block">
    <a href="<?=$arParams['PATH_TO_BASKET']?>" class="icon-link cart-toggler">
        <div class="icon icon-cart"></div>
        <span>Корзина</span>
        <span class="d-mobile"><?=\SaleFormatCurrency(
                $arResult['TOTAL_PRICE_RAW'],
                'RUB',
                true
            ) . ' ₽';?></span>
    </a>
</div>