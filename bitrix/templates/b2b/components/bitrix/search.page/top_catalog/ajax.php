<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="search-result">
    <? if (empty($arResult['SECTIONS']) && empty($arResult['ITEMS'])) : ?>
        Ничего не найдено!
    <? else: ?>
        <? if (!empty($arResult['SECTIONS'])) {
            ?>
            <div class="search-result-list">
                <? foreach ($arResult['SECTIONS'] as $sect) {
                    if(isset($sect['CNT']) && $sect['CNT'] == 0) continue;
                    ?>
                    <a href="<?= $sect['URL'] ?>"><?= $sect['NAME'] ?></a>
                <? } ?>
            </div>
        <? } ?>
        <? if (!empty($arResult['ITEMS'])) { ?>
            <? if (empty($arResult['SECTIONS'])): ?>
                <hr size="1px" color="#f2f2f2">
            <? endif; ?>
            <div class="search-result-list__items">
                <? foreach ($arResult['ITEMS'] as $item) { ?>
                    <a href="<?=$APPLICATION->GetCurDir()?>?q=<?= $item['TITLE'] ?>" class="search-result-list__item">
                        <img src="<?= $item['DETAIL_PICTURE'] ?>" alt="">
                        <p class="name"><?= $item['TITLE'] ?></p>
                        <? if (!empty($item['PRICE'])): ?> <p class="price">от <?= $item['PRICE'] ?> ₽</p><? endif; ?>
                    </a>
                <? } ?>
            </div>
        <? } ?>
    <? endif; ?>
</div>


