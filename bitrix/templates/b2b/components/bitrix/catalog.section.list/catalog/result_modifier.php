<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (0 < $arResult['SECTIONS_COUNT']) {
    $iconFields = [];
    $arSect = [];
    $arSelect = ['ID', 'UF_B2B_ICON'];

    foreach ($arResult['SECTIONS'] as $key => $arSection) {
        $arSect[$arSection['ID']] = $arSection;
    }
    unset($arResult['SECTIONS']);
    $arResult['SECTIONS'] = $arSect;

    $iconFields = [];
    $iconFieldDb = \CUserFieldEnum::GetList([], ['USER_FIELD_ID' => 278]);
    while ($ob_field = $iconFieldDb->fetch()) {
        $iconFields[$ob_field['ID']] = $ob_field['VALUE'];
    }

    $rsSections = CIBlockSection::GetList([], ['ID' => array_column($arResult['SECTIONS'], 'ID'), 'IBLOCK_ID' => $arParams['IBLOCK_ID']], false, $arSelect);
    while ($arSection = $rsSections->GetNext()) {

        if ($arSection['UF_B2B_ICON'])
            $arResult['SECTIONS'][$arSection['ID']]['ICON'] = $iconFields[$arSection['UF_B2B_ICON']];
    }
}


if ($arParams['SHOW_NEW'] == 'Y') {
    $cntAll = 0;
    global $newFilter;
    $newFilter = ['PROPERTY_HIT_VALUE' => 'Новинка'];
    $baseFilter = [
        'IBLOCK_ID' => CATALOG_IBLOCK_ID,
        'SECTION_ACTIVE' => 'Y',
        'SECTION_GLOBAL_ACTIVE' => 'Y',
        'INCLUDE_SUBSECTIONS' => 'Y',
        'ACTIVE' => 'Y'
    ];

    \Webfly\Helper\Functions::changeFilterOpt($baseFilter);


    $filter = array_merge($newFilter, $baseFilter);
    $res = \CIBlockElement::getList([], $filter, ['IBLOCK_SECTION_ID'], false, ['IBLOCK_SECTION_ID']);
    while ($ob = $res->fetch()) {
        $cntAll += $ob['CNT'];
    }
    $sections = \Webfly\Helper\Helper::getCatalogSections($filter);

    foreach ($sections as $section) {
        $sect = [
            'NAME' => $section['NAME'],
            'ICON' => $arResult['SECTIONS'][$section['ID']]['ICON'],
            'SECTION_PAGE_URL' => '/catalog/new/',
        ];
        $sectList[] = $sect;
    }

    $declension = new Bitrix\Main\Grid\Declension('товар', 'товара', 'товаров');
    $sectNew = [
        'NAME' => 'Новинки',
        'DEPTH_LEVEL' => '1',
        'ICON' => 'coffee-main',
        'SECTION_PAGE_URL' => '/catalog/new/',
        'CNT' => $cntAll,
        'LIST_SECTION' => $sectList
    ];
}

\Webfly\Helper\Functions::countElementOpt($arResult['SECTIONS']);

foreach ($arResult['SECTIONS'] as $key => $arSection) {
    if ($arSection["DEPTH_LEVEL"] == 1) {
        foreach ($arResult['SECTIONS'] as $key2 => $arSectionTwo) {
            if ($arSection['ID'] == $arSectionTwo['IBLOCK_SECTION_ID']) {
                $arResult['SECTIONS'][$key]['LIST_SECTION'][$arSectionTwo['ID']] = $arSectionTwo;
                unset($arResult['SECTIONS'][$key2]);
            }
        }
    }
}



if ($arParams['SHOW_NEW'] == 'Y') {
    $arSect = $arResult['SECTIONS'];
    unset($arResult['SECTIONS']);
    $arResult['SECTIONS'] = array_merge([$sectNew], $arSect);
}


?>