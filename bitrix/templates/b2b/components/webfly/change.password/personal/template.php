<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="container-size-0">
    <a class="icon-link mb-4" href="/personal/">
        <div class="icon icon-arrow-left"></div>
        <span>Назад</span>
    </a>
    <? if ($arResult['ERROR']): ?>
        <div class="alert alert-danger">
            <?= $arResult['ERROR'] ?>
        </div>
    <?elseif ($arResult['MESSAGE']):?>
        <div class="alert alert-success">
            <?= $arResult['MESSAGE'] ?>
        </div>
    <? endif ?>
    <h1 class="mb-4"> Сменить пароль </h1>
    <form action="<?= $APPLICATION->getCurDir() ?>" class="custom-form" id="change-password" method="post">
        <?= bitrix_sessid_post() ?>
        <div class="form-group">
            <div class="form-col">
                <div class="form-row">
                    <div class="form-cell">
                        <label class="custom-input">
                            <input type="password" placeholder="Введите пароль" name="OLD_PASSWORD" required>
                            <div class="input-title"> Старый пароль</div>
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-cell">
                        <label class="custom-input">
                            <input type="password" id="password" placeholder="Введите пароль" name="NEW_PASSWORD"
                                   required>
                            <div class="input-title"> Новый пароль</div>
                            <div class="tip"> <?= $arResult["GROUP_POLICY"]['PASS_TEXT'] ?></div>
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-cell">
                        <label class="custom-input">
                            <input type="password" id="confirm_password" placeholder="Введите пароль"
                                   name="NEW_PASSWORD_CONFIRM" required>
                            <div class="input-title"> Подтвердите пароль</div>
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-cell">
                        <button type="submit" class="button-full">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
