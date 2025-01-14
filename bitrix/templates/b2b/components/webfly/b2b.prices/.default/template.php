<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if ($arResult['PRICES']): ?>
    <div class="custom-select">
        <select name="prices" id="selectedPrices">
            <option value="" <?= empty($arResult['SELECTED_PRICE']) ? 'selected' : '' ?>>Цены без скидки</option>
            <? foreach ($arResult['PRICES'] as $key => $price): ?>
                <option value="<?=$key?>" <?= $arResult['SELECTED_PRICE'] == $key ? 'selected' : '' ?>>Цены для заказа
                    от <?= $price ?> рублей
                </option>
            <? endforeach ?>
        </select>
    </div>
    <script>
        let b2bPrices = new B2BPricesComponent();
        b2bPrices.init();
    </script>
<? endif ?>
