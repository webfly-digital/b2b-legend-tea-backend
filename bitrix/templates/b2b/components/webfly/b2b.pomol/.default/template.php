<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if ($arResult['POMOL_FIELDS']): ?>
    <div class="custom-select">
        <select name="pomol" id="catalog-pomol">
            <?if(empty($arResult['SELECTED'])):?>
            <option value="" selected>Сменить помол</option>
            <?endif?>
            <? foreach ($arResult['POMOL_FIELDS'] as $key => $name): ?>
                <option value="<?= $key ?>" <?= $arResult['SELECTED'] == $key ? 'selected' : '' ?>><?= $name ?></option>
            <? endforeach ?>
        </select>
    </div>
    <script>
        let b2bPomol = new B2BPomolComponent();
        b2bPomol.init();
    </script>
<? endif ?>
