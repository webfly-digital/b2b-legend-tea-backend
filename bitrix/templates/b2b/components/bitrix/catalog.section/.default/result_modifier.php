<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

if ($arParams['SECTIONS']) {

    $arResult['COLUMNS'] = [];
    foreach ($arParams['SECTIONS'] as $sId => $section) {
        $baseSection = CIBlockSection::GetNavChain($arResult['IBLOCK_ID'], $sId, ['ID'])->fetch();
        $arResult['BASE_SECTION'][$sId] = $baseSection;


        $sectionView = \Webfly\Helper\Helper::getSectionView($baseSection['ID']);
        if ($sectionView == 'SIMPLE') {
            $sectionView = \Webfly\Helper\Helper::getSectionView($sId);
        }


        if (!in_array($sectionView, ['UPAKOVKA_POMOL', 'UPAKOVKA']))
            $arResult['ADDITIONAL'][$sId]['CLASS'] = 'catalog-striped';


        switch ($sectionView) {
            case 'UPAKOVKA_POMOL':
                $arResult['COLUMNS'][$sId] = [
                    ['NAME' => 'Упаковка', 'CODE' => 'UPAKOVKA'],
                    ['NAME' => 'Помол', 'CODE' => 'POMOL']
                ];
                break;
            case 'UPAKOVKA':
                $arResult['COLUMNS'][$sId] = [
                    ['NAME' => 'Упаковка', 'CODE' => 'UPAKOVKA']
                ];
                break;
            case 'OBEM':
                $arResult['COLUMNS'][$sId] = [
                    ['NAME' => 'Объем', 'CODE' => 'OBEM']
                ];
                break;
            default:
                $arResult['COLUMNS'][$sId] = [];
                break;
        }
    }

    unset($baseSection);
    unset($sId);
    unset($section);


    $tree = [];
    $sections = [];

    /**
     * Простые товары без цены переносим в конец
     */
    if ($arParams['SORT'] != 'Y') {
        $priceItems = [];
        $noPriceItems = [];
        foreach ($arResult['ITEMS'] as $item) {
            $haveOffers = !empty($item['OFFERS']);
            if (!$haveOffers) {
                $price = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']];
                if (!$price) {
                    $noPriceItems[] = $item;
                    continue;
                }
            }
            $priceItems[] = $item;
        }
        $arResult['ITEMS'] = array_merge($priceItems, $noPriceItems);
    }

    foreach ($arResult['ITEMS'] as $key => $item) {
        if ($item['PROPERTIES']['HIT']['VALUE']) {
            switch (mb_strtolower($item['PROPERTIES']['HIT']['VALUE'])) {
                case 'новинка':
                    $item['LABEL'] = ['CLASS' => 'green', 'ICON' => 'new', 'TEXT' => 'Новинка'];
                    break;
                case 'хит':
                    $item['LABEL'] = ['CLASS' => 'red', 'ICON' => 'fire', 'TEXT' => 'Хит'];
                    break;
                case 'рекомендуем':
                    $item['LABEL'] = ['CLASS' => 'yellow', 'ICON' => 'thumb-up', 'TEXT' => 'Советуем'];
                    break;
                default:
                    break;
            }
        }
        $item['COLUMNS'] = $arResult['COLUMNS'][$item['~IBLOCK_SECTION_ID']];
        $tree[$item['~IBLOCK_SECTION_ID']][] = $item;
        if ($arParams['SEARCH'] == 'Y') {
            $arResult['ITEMS'][$key] = $item;
        }
    }
    if ($arParams['SEARCH'] == 'Y') {
        $arResult['COLUMNS_TITLE'] = [
            ['NAME' => 'Упаковка', 'CODE' => 'UPAKOVKA'],
            ['NAME' => 'Помол', 'CODE' => 'POMOL']
        ];
    }else {
        $arResult['ITEMS'] = $tree;
        $arResult['SECTIONS'] = $arParams['SECTIONS'];
        $arResult['ORIGINAL_PARAMETERS']['SECTIONS'] = $arResult['SECTIONS'];
    }

}

$this->__component->SetResultCacheKeys(array("NAV_RESULT"));

