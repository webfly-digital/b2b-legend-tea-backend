<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?php
if ($arResult["ITEMS"]):?>
    <section>
        <div class="three-cols-lk-container">
            <div class="left">
                <div class="nav simple-sticky">
                    <? foreach ($arResult["ITEMS"] as $arItem): ?>
                        <a data-scroll="<?= $arItem['ID'] ?>"><?= $arItem['NAME'] ?></a>
                    <? endforeach ?>
                </div>
            </div>
            <div class="mid">
                <div class="text-content">
                    <? foreach ($arResult["ITEMS"] as $arItem): ?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <section id="<?=$arItem['ID'] ?>">
                            <div class="title">
                                <h1><?=$arItem['NAME'];?></h1>
                            </div>
                            <?=$arItem['PREVIEW_TEXT']?>
                        </section>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<? endif; ?>