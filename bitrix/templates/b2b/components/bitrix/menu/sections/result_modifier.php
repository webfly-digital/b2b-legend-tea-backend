<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\Loader::includeModule('iblock');
if (!empty($arResult)) {
    $sections = [];
    $iblockId = 0;
    foreach ($arResult as $arItem) {
        if ($arItem['PARAMS']['SECTION_ID'] && $arItem['DEPTH_LEVEL'] == 1) {
            $sections[] = $arItem['PARAMS']['SECTION_ID'];
            $iblockId = $arItem['PARAMS']['IBLOCK_ID'];
        }
    }
    if ($sections) {
        $iconFields = [];
        $iconFieldDb = \CUserFieldEnum::GetList([], ['USER_FIELD_ID' => 278]);
        while ($ob_field = $iconFieldDb->fetch()) {
            $iconFields[$ob_field['ID']] = $ob_field['VALUE'];
        }

        $icons = [];
        $iconsDb = \CIBlockSection::GetList([], ['IBLOCK_ID' => $iblockId, 'ID' => $sections], false, ['ID', 'IBLOCK_ID', 'UF_B2B_ICON'], false);
        while ($ob = $iconsDb->fetch()) {
            if ($ob['UF_B2B_ICON'])
                $icons[$ob['ID']] = $iconFields[$ob['UF_B2B_ICON']];
        }

        unset ($ob);
        unset ($ob_field);

        if ($icons) {
            foreach ($arResult as &$arItem) {
                if ($icons[$arItem['PARAMS']['SECTION_ID']]) {
                    $arItem['PARAMS']['ICON'] = $icons[$arItem['PARAMS']['SECTION_ID']];
                }
            }
        }

    }
}
