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

<? if (0 < $arResult["SECTIONS_COUNT"]): ?>
    <div class="catalog-sections">
        <div class="catalog-sections-list">
            <? foreach ($arResult['SECTIONS'] as  &$arSection) :
                $active = '';
                $query = $arParams['QUERY_LIST'];
                if ($arParams['QUERY_LIST']["sections"] && in_array($arSection['ID'], $arParams['QUERY_LIST']["sections"])) {
                    $key = array_search($arSection['ID'], $arParams['QUERY_LIST']["sections"]);
                    if (isset($key)) unset($query['sections'][$key]);
                    $active = 'active';
                } else {
                    $query['sections'][] = $arSection['ID'];
                }

                $link = $arParams['PAGE_DIR'] . '?' . http_build_query($query);
                ?>
                <a href="<?= $link ?>" class="<?= $active ?>">
                    <p><?= $arSection['NAME'] ?></p>
                    <span><?= $arSection['ELEMENT_CNT'] ?></span>
                </a>
            <? endforeach; ?>
            <? if (count($arResult['SECTIONS']) > 10 ): ?>
                <div class="else-button">
                    <p>Смотреть еще</p>
                    <span>+<?= count($arResult['SECTIONS']) - 10 ?></span>
                </div>
            <? endif ?>
        </div>
    </div>
<? endif ?>
