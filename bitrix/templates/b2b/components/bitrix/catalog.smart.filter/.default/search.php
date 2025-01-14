<?
global $sectionFilter;
$sectionFilter = ['PARAMS'=>['iblock_section'=>$arParams['SECTION_ID']]];
?>
<?
$arElements = $APPLICATION->IncludeComponent(
    "bitrix:search.page",
    ".default",
    [
        "RESTART" => $arParams['SEARCH_PARAMS']["RESTART"],
        "NO_WORD_LOGIC" => $arParams['SEARCH_PARAMS']["NO_WORD_LOGIC"],
        "USE_LANGUAGE_GUESS" => $arParams['SEARCH_PARAMS']["USE_LANGUAGE_GUESS"],
        "CHECK_DATES" => $arParams['SEARCH_PARAMS']["CHECK_DATES"],
        "arrFILTER" => [
            "iblock_".$arParams['SEARCH_PARAMS']["IBLOCK_TYPE"],
        ],
        "arrFILTER_iblock_".$arParams['SEARCH_PARAMS']["IBLOCK_TYPE"] => [
            $arParams['SEARCH_PARAMS']["IBLOCK_ID"],
        ],
        "USE_TITLE_RANK" => $arParams['SEARCH_PARAMS']['USE_TITLE_RANK'],
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "sectionFilter",
        "SHOW_WHERE" => "N",
        "arrWHERE" => [],
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" =>500,
        "USE_SEARCH_RESULT_ORDER" => $arParams['SEARCH_PARAMS']['USE_SEARCH_RESULT_ORDER'],
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "N",
    ],
    $component,
    [
        'HIDE_ICONS' => 'Y',
    ]
);?>
<?
if (!empty($arElements) && is_array($arElements))
{
    $searchFilter = [
        "ID" => $arElements,
    ];
    if ($arParams['SEARCH_PARAMS']['USE_SEARCH_RESULT_ORDER'] === 'Y')
    {
        $elementOrder = [
            "ELEMENT_SORT_FIELD" => "ID",
            "ELEMENT_SORT_ORDER" => $arElements,
        ];
    }
}
?>
<?
global $SECTION_SEARCH_RESULT;
$SECTION_SEARCH_RESULT = ['ID'=>$arElements, 'ORDER'=>$elementOrder];
?>
