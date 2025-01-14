<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if (!empty($arResult['ERRORS'])) {
    $component = $this->__component; ?>
    <div class="container-size-3 mb-2">
        <?
        foreach ($arResult['ERRORS'] as $code => $error) {
            if ($code !== $component::E_NOT_AUTHORIZED)
                ShowError($error);
        } ?>
    </div>
    <?
    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS'][$component::E_NOT_AUTHORIZED])) {
        ?>
        <? $authListGetParams = array(); ?>
        <? $APPLICATION->AuthForm('', false, false, 'N', false); ?>
        <?
        return;
    }
}
?>
<div class="container-size-3">
    <div class="companies-page">
        <h1><? $APPLICATION->showTitle(); ?></h1>
        <div class="note-plate">Это список компаний, адресов, на которые вы оформляли заказы. Для создания нового
            профиля можно оформить новый заказ с измененными данными и он автоматически добавиться в этот список. Или
            можно создать его в ручном режиме по кнопке ниже "создать новую компанию"
        </div>
        <?php
        if (count($arResult["PROFILES"])) {
            ?>
            <div class="companies-table">
                <div class="companies-table-header">
                    <div class="cell">Профиль</div>
                    <div class="cell">Юр.лицо</div>
                    <div class="cell">Адрес доставки</div>
                    <div class="cell">Действия</div>
                </div>
                <div class="companies-table-body">
                    <? foreach ($arResult["PROFILES"] as $val): ?>
                        <div class="companies-table-row">
                            <div class="companies-table-cell">
                                <span>
                                    <a href="<?= $val["URL_TO_DETAIL"] ?>"><?= $val['NAME'] ?></a>
                                </span>
                            </div>
                            <div class="companies-table-cell">
                                <span><?= $val['COMPANY_NAME'] ?: '-' ?></span>
                            </div>
                            <div class="companies-table-cell">
                                <span><?= $val['FULL_ADDRESS'] ?: '-' ?></span>
                            </div>
                            <div class="companies-table-cell">
                                <a href="<?= $val["URL_TO_DETAIL"] ?>" class="link text-grey1">Изменить</a>
                                <a href="javascript:if(confirm('<?= Loc::getMessage("STPPL_DELETE_CONFIRM") ?>')) window.location='<?= $val["URL_TO_DETELE"] ?>'"
                                   class="link">Удалить</a>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
                <div class="companies-table-footer">
                    <div class="icon-link">
                        <div class="icon icon-plus"></div>
                        <a href="<?= $APPLICATION->getCurDir(); ?>0" class="link"> Создать новую компанию </a>
                    </div>
                </div>
            </div>
            <?
            if ($arResult["NAV_STRING"] <> '') {
                ?>
                <br>
                <?= $arResult["NAV_STRING"] ?>

                <?
            }
        } else {
            ?>
            <h3><?= Loc::getMessage("STPPL_EMPTY_PROFILE_LIST") ?></h3>
            <div class="companies-table-footer">
                <div class="icon-link">
                    <div class="icon icon-plus"></div>
                    <a href="<?= $APPLICATION->getCurDir(); ?>0" class="link"> Создать новую компанию </a>
                </div>
            </div>
            <?
        }
        ?>
    </div>
</div>
