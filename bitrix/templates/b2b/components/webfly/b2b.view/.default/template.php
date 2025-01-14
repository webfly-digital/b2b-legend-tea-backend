<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="icon-toggler">
    <input class="icon-on-input" name="view" type="radio"
           id="catalog-view-image" <?= $arResult ["SELECTED"] == 'image' ? 'checked' : '' ?> value="image">
    <label class="icon-on-label" for="catalog-view-image"></label>
    <input class="icon-off-input" name="view" type="radio" id="catalog-view-list" <?= $arResult ["SELECTED"] == 'list' ? 'checked' : '' ?> value="list">
    <label class="icon-off-label" for="catalog-view-list"></label>
</div>
<script>
    let b2bView = new B2BViewComponent();
    b2bView.init();
</script>

