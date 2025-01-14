<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if ($arResult['ITEMS']){
    foreach ($arResult['ITEMS'] as &$arItem){
        if ($arItem["PREVIEW_PICTURE"]['ID']){
            $arItem['PICTURE'] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], ['width'=>363, 'height'=>204], BX_RESIZE_IMAGE_EXACT, false, false, false, 100);
        }
    }

}
