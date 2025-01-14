<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult["ITEMS"]){
    $arResult['CHECKED'] = [];
    foreach ($arResult["ITEMS"] as $key => $arItem){
        if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
            continue;
        /**
         * skip: NUMBERS_WITH_SLIDER, NUMBERS, CALENDAR
         */
        if ($arItem["DISPLAY_TYPE"] == "A" || $arItem["DISPLAY_TYPE"] == "B" || $arItem["DISPLAY_TYPE"] == "U")
            continue;

        $arCur = current($arItem["VALUES"]);

        foreach ($arItem["VALUES"] as $val => $ar){
            if ($ar["CHECKED"] && !$ar["DISABLED"] ){
                if (!isset($arResult['CHECKED'][$arItem['ID']]))
                    $arResult['CHECKED'][$arItem['ID']]['PROPERTY'] = $arItem;

                $arResult['CHECKED'][$arItem['ID']]['VALUES'][] = $ar;
            }
        }
    }
}
