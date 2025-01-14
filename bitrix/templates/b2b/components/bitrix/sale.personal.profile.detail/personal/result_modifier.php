<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!$arResult["ID"]) return;
/**
 * Коды свойств -> в ключи (для удобства)
 */
$arResult["ORDER_FIELDS"] = [];
if ($arResult["ORDER_PROPS"]){
    foreach ($arResult["ORDER_PROPS"] as $blockKey => $block) {
        if (!empty($block["PROPS"])) {
            foreach ($block["PROPS"] as $property){
                $arResult["ORDER_FIELDS"][$property['CODE']] = $property;
            }
        }
    }

}
