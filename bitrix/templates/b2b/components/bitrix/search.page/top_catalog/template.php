<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true); ?>
<?

CUtil::InitJSCore(array('ajax'));
if (isset($_REQUEST["q"]) && isset($_REQUEST["ajax_call"]) && $_REQUEST["ajax_call"] === "y") {
    include_once 'ajax.php';
} else {
    $INPUT_ID = trim($arParams["~INPUT_ID"]);
    if ($INPUT_ID == '')
        $INPUT_ID = "title-search-input";
    $INPUT_ID = CUtil::JSEscape($INPUT_ID);

    $CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
    if ($CONTAINER_ID == '')
        $CONTAINER_ID = "title-search";
    $CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);
    $APPLICATION->AddHeadScript($templateFolder . '/script.js');

    $str = $arParams["STR_SEARCH"]?:$APPLICATION->GetCurDir();


    if ($arParams["SHOW_INPUT"] !== "N"):?>
        <form action="<?=$str?>" method="get" id="<? echo $CONTAINER_ID ?>">
            <input id="<? echo $INPUT_ID ?>" type="text" name="q" size="40" maxlength="50" value="<?=$_REQUEST["q"]?:''?>"
                   placeholder="Поиск товара по артикулу или названию" autocomplete="off"
                   autocomplete="off"/>
        </form>
    <? endif ?>
    <script>
        BX.ready(function () {
            new JCTitleSearch({
                'AJAX_PAGE': '<?echo CUtil::JSEscape($str)?>',
                'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
                'INPUT_ID': '<?echo $INPUT_ID?>',
                'MIN_QUERY_LEN': 2
            });
        });
    </script>
<? } ?>