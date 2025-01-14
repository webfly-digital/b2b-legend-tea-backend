<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Text\HtmlFilter;

/**
 * @var StringUfComponent $component
 * @var array $arResult
 */

?>
<div class="form-row" <?if ($arResult["userField"]['group']):?>data-control-group="<?=$arResult["userField"]['group']?>" data-control-mode="<?=$arResult["userField"]['mode']?>" data-control-inverted="<?=$arResult["userField"]['inverted']?>" data-control-type="<?=$arResult["userField"]['type']?>"<?endif?>>
    <div class="form-cell">
        <label class="custom-input">
            <input minlength="3" name="<?= $arResult["userField"]["FIELD_NAME"] ?>" type="text" value="<?=$arResult['USER_VALUE']?:''?>" placeholder="<?= $arResult["userField"]["SETTINGS"]["DEFAULT_VALUE"] ?>" <?if ($arResult["userField"]['pattern']):?>pattern="<?=$arResult["userField"]['pattern']?>"<?endif?> <?=$arResult["userField"]['required']?'required':''?>>
            <div class="input-title"><?=$arResult["userField"]["EDIT_FORM_LABEL"]?><?=$arResult["userField"]['required']?'*':''?></div>
            <?if ($arResult["userField"]["HELP_MESSAGE"]):?><div class="tip"><?= $arResult["userField"]["HELP_MESSAGE"] ?></div><?endif?>
        </label>
    </div>
</div>

