<div class="form-cell">
    <label class="custom-input">
        <input type="<?=$type?:'text'?>" name="PROPERTY[<?= $property['ID'] ?>]" placeholder="<?= $property['HINT']?:' ' ?>"
               minlength='2' maxlength="250"
               value='<?=$currentValue?>' <?=$property['IS_REQUIRED']=='Y'?'required':''?> <?if ($pattern):?>pattern="<?=$pattern?>"<?endif?>/>
        <div class="input-title"><?= $property['NAME'] ?><?=$property['IS_REQUIRED']=='Y'?'*':''?></div>
    </label>
</div>