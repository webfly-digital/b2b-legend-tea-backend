<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\UserField\Types\BaseType;
use Bitrix\Main\UserField\Types\EnumType;

/**
 * @var EnumUfComponent $component
 * @var array $arResult
 */
$component = $this->getComponent();
$showSelect = isset($arResult['userField']['USER_TYPE']['FIELDS']) && is_array($arResult['userField']['USER_TYPE']['FIELDS']);
?>
<? if ($showSelect): ?>
    <select name="<?= $arResult["userField"]["FIELD_NAME"] ?>"
            <? if ($arResult["userField"]['group']): ?>data-control-group="<?= $arResult["userField"]['group'] ?>"
            data-control-mode="<?= $arResult["userField"]['mode'] ?>" data-control-type="<?=$arResult["userField"]['type']?>"<? endif ?>
            data-control-inverted="<?= $arResult["userField"]['inverted'] ?>" <?=$arResult["userField"]['required']?'required':''?>>
        <?
        $isWasSelect = false;
        foreach ($arResult['userField']['USER_TYPE']['FIELDS'] as $key => $val) {
            $isSelected = (
                in_array($key, $arResult['value'])
                &&
                (
                    (!$isWasSelect) || ($arResult['userField']['MULTIPLE'] === 'Y')
                )
            );
            $isWasSelect = $isWasSelect || $isSelected; ?>
            <option value="<?= HtmlFilter::encode($key) ?>" <?= ($isSelected ? ' selected="selected"' : '') ?>><?= $val ?></option>
            <?
        }
        ?>
    </select>
    <? if ($arResult["userField"]['title']): ?>
        <div class="input-title"> <?=$arResult["userField"]["EDIT_FORM_LABEL"]?><?=$arResult["userField"]['required']?'*':''?></div>
    <? endif ?>

<? endif ?>
