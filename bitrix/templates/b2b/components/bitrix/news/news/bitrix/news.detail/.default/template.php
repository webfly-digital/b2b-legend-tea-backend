<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<div class="title">
    <h1><?=$arResult['NAME']?></h1>
    <div class="subtitle"><?=$arResult['PREVIEW_TEXT']?></div>
</div>
<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["PICTURE"])):?>
    <img
            src="<?=$arResult["PICTURE"]["src"]?>"
            alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
            title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
    />
<?endif?>
<?echo $arResult["DETAIL_TEXT"];?>
<div class="footer"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
