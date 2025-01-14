<?php
\Bitrix\Main\Loader::includeModule('sale');
if (count($arResult["PROFILES"])){
    foreach ($arResult["PROFILES"] as $key=> $val) {
        $res = \Bitrix\Sale\OrderUserProperties::getProfileValues($val['ID']);
        if ($val["PERSON_TYPE_ID"] == B2B_UR_PERSON_TYPE_ID){
            $arResult["PROFILES"][$key]['COMPANY_NAME'] = $res[43];
            $arResult["PROFILES"][$key]['ADDRESS'] = $res[48];
            $locationPropId = 42;
        }else{
            $arResult["PROFILES"][$key]['ADDRESS'] = $res[58];
            $locationPropId = 53;
        }

        if ($res[$locationPropId]){
            $cityInfo = \Bitrix\Sale\Location\LocationTable::getList(array(
                'filter' => array('=NAME.LANGUAGE_ID' => 'ru', 'CODE'=>$res[$locationPropId]),
                'select' => array('NAME_RU' => 'NAME.NAME'),
                'limit'=>1
            ))->fetch();
            if ($cityInfo["NAME_RU"]){
                $arResult["PROFILES"][$key]['CITY_NAME'] = $cityInfo["NAME_RU"];
            }
        }
        if ($arResult["PROFILES"][$key]['CITY_NAME'])
            $arResult["PROFILES"][$key]['FULL_ADDRESS'] = $arResult["PROFILES"][$key]['CITY_NAME'];

        if ($arResult["PROFILES"][$key]['ADDRESS']){
            $arResult["PROFILES"][$key]['FULL_ADDRESS'] = $arResult["PROFILES"][$key]['FULL_ADDRESS']?implode(', ', [$arResult["PROFILES"][$key]['FULL_ADDRESS'], $arResult["PROFILES"][$key]['ADDRESS']]):$arResult["PROFILES"][$key]['ADDRESS'];
        }
    }
}
