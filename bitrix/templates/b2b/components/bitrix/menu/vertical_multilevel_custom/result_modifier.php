<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\Loader::includeModule('iblock');

if (!empty($arResult)) {
    $sections = [];

    foreach ($arResult as &$arItem) {
        $cur_url = explode("/", $arItem['LINK']);
        if (!empty($cur_url[3])) {
            $sections[] = $cur_url[3];
            $arItem['CODE'] = $cur_url[3];
        } else {
            $sections[] = $cur_url[2];
            $arItem['CODE'] = $cur_url[2];
        }

        if ($arItem['TEXT'] == "Ароматизированный кофе Легенда чая") $arItem['TEXT'] = "Аром. кофе Легенда чая";
        if (strpos($arItem['TEXT'], "(Ингредиенты)") !== false) $arItem['TEXT'] = explode("(", $arItem['TEXT'])[0];
    }

    if ($sections) {


        $iconFields = [];
        $iconFieldDb = \CUserFieldEnum::GetList([], ['USER_FIELD_ID' => 278]);
        while ($ob_field = $iconFieldDb->fetch()) {
            $iconFields[$ob_field['ID']] = $ob_field['VALUE'];
        }

        $icons = [];
        $iconsDb = \CIBlockSection::GetList([], ['IBLOCK_ID' => CATALOG_IBLOCK_ID, 'CODE' => $sections], false, ['CODE', 'IBLOCK_ID', 'UF_B2B_ICON', 'ID'], false);
        while ($ob = $iconsDb->fetch()) {
            if ($ob['UF_B2B_ICON']) {
                $icons[$ob['CODE']] = $iconFields[$ob['UF_B2B_ICON']];
            }
            $arResultCode[$ob['CODE']] = $ob['ID'];
        }

        unset ($ob);
        unset ($ob_field);
        global $USER;
        if ($USER->IsAdmin()) {

            if ($arResultCode) {
                foreach ($arResult as $item) {
                    if ($arResultCode[$item['CODE']]) $arNewResult[$arResultCode[$item['CODE']]] = $item;
                }
            }
            if ($arNewResult) {
                $arResult = [];
                $arResult = $arNewResult;
                \Webfly\Helper\Functions::countElementOpt($arResult);
            }
        }


        if ($icons) {
            foreach ($arResult as &$arItem) {
                $cur_url = explode("/", $arItem['LINK']);
                if (!empty($cur_url[3])) $code = $cur_url[3];
                else $code = $cur_url[2];
                if ($icons[$code]) $arItem['PARAMS']['ICON'] = $icons[$code];


                if ($code == 'aromatizirovannyy_cofe') $arItem['TEXT'] = 'Аром. кофе Легенда чая';
                if ($code == 'cherno_zelenyy_s_dobavkami') $arItem['TEXT'] = 'Черно-зеленый чай с доб.';
                if ($code == 'prigotovlenie_kofe_alternativa') $arItem['TEXT'] = 'Приготовление кофе (альт.)';
            }
        }

    }
}
