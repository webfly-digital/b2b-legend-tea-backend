<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if ($arResult["DETAIL_PICTURE"]){
    $arResult['PICTURE'] = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"]['ID'], ['width'=>1200, 'height'=>800], BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 100);
}
