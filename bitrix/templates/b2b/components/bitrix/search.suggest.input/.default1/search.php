<?
define("STOP_STATISTICS", true);
define("PUBLIC_AJAX_MODE", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset='.LANG_CHARSET);
CUtil::JSPostUnescape();

$arResult = $arResultSections = $arSections = array();

if(CModule::IncludeModule("search"))
{
	if(!empty($_POST["search"]) && is_string($_POST["search"]))
	{
		$search = $_POST["search"];

		$arParams = array();
		$params = explode(",", $_POST["params"]);
		foreach($params as $param) {
			list($key, $val) = explode(":", $param);
			$arParams[$key] = $val;
		}
        /*$obSearch = new CSearch;
        $obSearch->SetOptions(array(
            'ERROR_ON_EMPTY_STEM' => false,
        ));
        $obSearch->Search(array(
            "QUERY" => $search,
            "SITE_ID" => "s3",
            "MODULE_ID" => "iblock",
            //"PARAM1" => '1c_catalog',
            "PARAM2" => 93
        ), array(), array('STEMMING' => false));
        $obSearch->SetLimit(10);
        while($result = $obSearch->GetNext()) {
            $sec_id = '';
            $tmp = array(
                "ID" => $result["ITEM_ID"],
                "NAME" => $result["TITLE"],
                "URL" => $result["URL"],
                "CNT" => intval($result["RANK"]),
                "PREVIEW" => '',
                "PRICE" => 0
            );*/
            $arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "DETAIL_PICTURE", "IBLOCK_SECTION_ID", "CATALOG_GROUP_19");
            $arFilter = Array("IBLOCK_ID"=>93, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "%NAME"=>$search);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>3), $arSelect);
            while($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();

                $tmp = array(
                    "ID" => $arFields["ID"],
                    "NAME" => $arFields["NAME"],
                    "URL" => $arFields["CODE"],
                    "CNT" => 1,
                    "PREVIEW" => '',
                    "PRICE" => 0
                );

                $sec_id = $arFields["IBLOCK_SECTION_ID"];
                $tmp["SECTION"] = $arFields["IBLOCK_SECTION_ID"];
                if ($arFields["DETAIL_PICTURE"]) $tmp["PREVIEW"] = CFile::GetPath($arFields["DETAIL_PICTURE"]);
                if (intval($arFields["CATALOG_PRICE_19"])>0) {
                    $tmp["PRICE"] = $arFields["CATALOG_PRICE_19"];
                }

                $arResult[] = $tmp;
            }

            //$arSections[$sec_id] = $sec_id;

        //}

        /*$obSearch = new CSearch;
        $obSearch->SetOptions(array(
            'ERROR_ON_EMPTY_STEM' => false,
        ));
        $obSearch->Search(array(
            "QUERY" => $search,
            "SITE_ID" => "s3",
            "MODULE_ID" => "iblock",
            "ITEM_ID" => '%S',
            "PARAM2" => 93
        ), array(), array('STEMMING' => false));
        while($result = $obSearch->GetNext()) {*/
            $arFilter = Array('IBLOCK_ID'=>93, 'GLOBAL_ACTIVE'=>'Y', '%NAME'=>$search);
            $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
            $db_list->NavStart(3);
            while($ar_result = $db_list->GetNext()) {
                $arResultSections[] = array(
                    "ID" => $ar_result["ID"],
                    "NAME" => $ar_result["NAME"],
                    "URL" => "/catalog/".$ar_result["CODE"]."/",
                );
            }

        //}


        /*
		$obSearchSuggest = new CSearchSuggest($arParams["md5"], $search);

		$db_res = $obSearchSuggest->GetList($arParams["pe"], $arParams["site"]);
		if($db_res)
		{
			while($res = $db_res->Fetch())
			{
				$arResult[] = array(
					"NAME" => $res["PHRASE"],
					"CNT" => intval($res["CNT"]),
				);
			}
		}*/
	}
}
/*
if (!empty($arSections)) {
    foreach ($arSections as $sec) {
        $arFilter = Array('IBLOCK_ID'=>93, 'GLOBAL_ACTIVE'=>'Y', 'ID'=>$sec);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
        while($ar_result = $db_list->GetNext()) {
            $arResultSections[] = array(
                "ID" => $ar_result["ID"],
                "NAME" => $ar_result["NAME"],
                "URL" => "/catalog/".$ar_result["CODE"]."/",
            );
        }
    }
}*/

?>

    <div class="search-result">

        <? $i=0; if (!empty($arResultSections)) {?>
            <div class="search-result-list">
                <? foreach ($arResultSections as $sec) {?>
                    <? if ($i < 3) {?>
                    <a href="<?=$sec["URL"]?>"><?=$sec["NAME"]?></a>
                    <?}?>
                <?$i++;}?>
            </div>
        <?}?>
        <hr size="1px" color="#f2f2f2">
        <? $i=0; if (!empty($arResult)) {?>
            <div class="search-result-list__items">
            <? foreach ($arResult as $itm) {
                if ($i < 3) {

                    $price = 0;
                    $arMin = [];
                    $arSelect2 = Array("ID", "NAME", "IBLOCK_ID", "CATALOG_GROUP_19");
                    $arFilter2 = Array("IBLOCK_ID"=>93, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_CML2_LINK"=>$arFields["ID"]);
                    $res2 = CIBlockElement::GetList(Array(), $arFilter2, false, false, $arSelect2);
                    while($ob2 = $res2->GetNextElement()) {
                        $arFields2 = $ob2->GetFields();
                        $arMin[$arFields2["CATALOG_PRICE_19"]] = $arFields2["CATALOG_PRICE_19"];
                    }
                    if (!empty($arMin)) $price = "от ".min($arMin);
                    $pic = '/upload/delight.webpconverter/bitrix/templates/b2b/assets/static/img/img-holder2.png.webp';
                    if ($itm["PREVIEW"]) $pic = $itm["PREVIEW"];
                    ?>
                    <a href="javascript:void(0)" class="search-result-list__item search-show-more" data-id="<?=$itm["ID"]?>">
                        <img src="<?=$pic?>" alt="">
                        <p class="name"><?=$itm["NAME"]?></p>
                        <? if (intval($itm["PRICE"])>0) {?><p class="price"><?=CurrencyFormat($itm["PRICE"], "RUB")?> ₽</p><?}?>
                    </a>
                <?}$i++;}?>
            </div>
        <?}?>

    </div>

<?

//echo CUtil::PhpToJSObject($arResult);

CMain::FinalActions();
die();
