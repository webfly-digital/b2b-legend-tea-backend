<div class="form-cell <?=$cellClass?>" id="<?=$code?>">
    <label class="custom-input">
        <input <?=$readOnly?'readonly':''?> data-code="<?=$code?>" type="<?=$type?>" name="<?= $name ?>" placeholder="<?= $property['DESCRIPTION']?:' ' ?>"
               minlength='2' maxlength="250" id="sppd-property-<?= $property['ID'] ?>"
                                            value="<?= $currentValue ?>" <?= $property["REQUIED"] == "Y" ? 'required' : '' ?> <?if ($pattern):?>pattern="<?=$pattern?>"<?endif?>/>
        <div class="input-title"> <?= $property["NAME"] ?><?= $property["REQUIED"] == "Y" ? '*' : '' ?> </div>
        <?=$readOnly?'':'<span class="err-msg"></span>'?>
    </label>
</div>
