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
<div class="three-cols-table-container">
    <div class="left"></div>
    <div class="mid">
        <section>
            <div class="section-title">
                <h1><?= $arResult['NAME'] ?></h1>
            </div>
            <div class="text"><?= $arResult["~DESCRIPTION"] ?></div>
            <? foreach ($arResult["SECTIONS"] as $arSect): ?>
                <div class="documents-block">
                    <h3><?= $arSect['NAME'] ?></h3>
                    <ul>
                        <? foreach ($arResult["ITEMS"] as $arItem):
                            if ($arItem['IBLOCK_SECTION_ID'] == $arSect['ID']):

                                if ($arItem["PROPERTIES"]["LINK"]["VALUE"]) {
                                    $link = $arItem["PROPERTIES"]["LINK"]["VALUE"];
                                }
                                if ($arItem["PROPERTIES"]["FILE"]["VALUE"]) {
                                    $link = CFile::GetPath($arItem["PROPERTIES"]["FILE"]["VALUE"]);
                                }
                                ?>
                                <li>
                                    <div class="documents-item">
                                        <div class="documents-item-icon"></div>
                                        <a target="_blank" href="https://<?=SITE_SERVER_NAME.$link?>" class="subtitle"><?= $arItem['NAME'] ?></a>
                                        <a style="position: absolute;
    top: 24px;
    right: 24px;
    border-radius: 6px;
    cursor: pointer;" target="_blank" href="https://<?=SITE_SERVER_NAME.$link?>" class="icon icon-download"></a>
                                    </div>
                                </li>
                            <? endif ?>
                        <? endforeach; ?>
                    </ul>
                </div>
            <? endforeach; ?>
        </section>
    </div>
    <div class="right">
    </div>
</div>

