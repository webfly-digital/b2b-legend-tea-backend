<? if ($isFilter): ?>
    <? $APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "", array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "SECTION_ID" => $arCurSection['ID'],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "PRICE_CODE" => $arParams["~PRICE_CODE"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SAVE_IN_SESSION" => "N",
        "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
        "XML_EXPORT" => "N",
        "SECTION_TITLE" => "NAME",
        "SECTION_DESCRIPTION" => "DESCRIPTION",
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
        "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        "SEF_MODE" => $arParams["SEF_MODE"],
        "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
        "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
        "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
        "PREFILTER_NAME" => '',
        "SEARCH_PARAMS" => [
            'RESTART' => $arParams["SEARCH_RESTART"],
            'NO_WORD_LOGIC' => $arParams["SEARCH_NO_WORD_LOGIC"],
            'USE_LANGUAGE_GUESS' => $arParams["SEARCH_USE_LANGUAGE_GUESS"],
            'CHECK_DATES' => $arParams["SEARCH_CHECK_DATES"],
            'IBLOCK_TYPE' => $arParams["IBLOCK_TYPE"],
            'IBLOCK_ID' => $arParams["IBLOCK_ID"],
            'USE_TITLE_RANK' => $arParams["SEARCH_USE_TITLE_RANK"],
            'PAGE_RESULT_COUNT' => $arParams["SEARCH_PAGE_RESULT_COUNT"],
            'USE_SEARCH_RESULT_ORDER' => $arParams['SEARCH_USE_SEARCH_RESULT_ORDER']
        ]
    ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
    ?>
<? endif ?>
