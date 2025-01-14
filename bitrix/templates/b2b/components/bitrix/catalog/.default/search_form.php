<div class="search">
    <?if (isset($_REQUEST["q"]) && isset($_REQUEST["ajax_call"]) && $_REQUEST["ajax_call"] === "y") $APPLICATION->RestartBuffer();

    $arSearchElementsTop = $APPLICATION->IncludeComponent(
        "bitrix:search.page",
        "top_catalog",
        array(
            "RESTART" => "Y",
            "NO_WORD_LOGIC" => "Y",
            "CHECK_DATES" => "Y",
            "USE_TITLE_RANK" => "Y",
            "DEFAULT_SORT" => "rank",
            "FILTER_NAME" => "",
            "arrFILTER" => array(
                0 => "iblock_1c_catalog",
            ),
            "arrFILTER_iblock_1c_catalog" => array(
                0 => "93",
            ),
            "SHOW_WHERE" => "N",
            "SHOW_WHEN" => "N",
            "PAGE_RESULT_COUNT" => "6",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "USE_LANGUAGE_GUESS" => "N",
            "SHOW_RATING" => "",
            "RATING_TYPE" => "",
            "PATH_TO_USER_PROFILE" => "",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Результаты поиска",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ".default",
            "USE_SUGGEST" => "N",
            "FROM_AJAX" => "",
            "COMPONENT_TEMPLATE" => "b2b"
        ),
        false
    );


    if (isset($_REQUEST["q"]) && isset($_REQUEST["ajax_call"]) && $_REQUEST["ajax_call"] === "y") die;
    $searchInitialised = $request->get('q') ? true : false; ?>
</div>
