<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if ($arResult['SORT_FIELDS']):?>
<div class="custom-select">
    <select name="sort" id="catalog-sort">
        <option value="" data-order="" <?=$arResult['REQUEST']?'':'selected'?>>Сортировать</option>
        <?foreach ($arResult['SORT_FIELDS'] as $sort => $name):?>
            <option value="<?=$sort?>" data-order="desc" <?=$arResult['REQUEST']['sort']==$sort && $arResult['REQUEST']['order']=='desc'?'selected':''?>><?=$name?> (убывание)</option>
            <option value="<?=$sort?>" data-order="asc" <?=$arResult['REQUEST']['sort']==$sort && $arResult['REQUEST']['order']=='asc'?'selected':''?>><?=$name?> (возрастание)</option>
        <?endforeach?>
    </select>
</div>
<script>
    let b2bOrder = new B2BOrderComponent();
    b2bOrder.init();
</script>
<?endif?>
