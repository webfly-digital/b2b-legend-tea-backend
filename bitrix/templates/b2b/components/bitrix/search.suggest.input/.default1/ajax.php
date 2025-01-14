<?
define("STOP_STATISTICS", true);
define("PUBLIC_AJAX_MODE", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
$arResult = $arResultSections = $arSections = array();

if ($_REQUEST["id"]) {

$arPropsShow = [
    0 => "VID_KOFE",
			1 => "OBRABOTKA",
			2 => "RAZNOVIDNOST",
			3 => "REGION",
			4 => "CML2_BASE_UNIT",
			5 => "ZAMETKI_KAPPINGA",
			6 => "CML2_MANUFACTURER",
			7 => "CML2_BAR_CODE",
			8 => "RAZMER_ZERNA",
			9 => "VYSOTA_PROIZRASTANIYA",
			10 => "TSVET_1",
			11 => "OTSENKA_Q_GRADER",
			12 => "STEPEN_OBZHARKI",
			13 => "ROSTER_DLYA_OBZHARKI",
			14 => "POMOL",
			15 => "FORMA_VYPUSKA",
			16 => "MATERIAL",
			17 => "VID_CHAYA",
			18 => "OSNOVA",
			19 => "OBRABOTKA_1",
			20 => "KOLICHESTVO_OSADKOV",
			21 => "POCHVA",
			22 => "TIP_PAKETA_1",
			23 => "PODKHODIT_DLYA",
			24 => "TIP_KOFEMASHINY",
			25 => "VYSOTA_MM",
			26 => "KOLICHESTVO_GRUPP",
			27 => "NAPRYAZHENIE",
			28 => "MOSHCHNOST",
			29 => "OBEM_BOYLERA",
			30 => "TIP_SLADOSTI",
			31 => "VMESTIMOST_PRIMERNO_1",
			32 => "PLOTNOST",
			33 => "SHIRINA_MM_1",
			34 => "GLUBINA_MM_1",
			35 => "FABRIKA",
			36 => "VES_UPAKOVKI",
			37 => "VYSOTA_MM_1",
			38 => "KLAPAN_1",
			39 => "POD_ZAPAYKU_1",
			40 => "OKOSHKO",
			41 => "DATA_PROIZVODSTVA",
			42 => "KATEGORIYA_CHAYA",
			43 => "OBEM",
			44 => "ORIDZHIN",
			45 => "KISLOTNOST",
			46 => "TIP_POSUDY",
			47 => "UPAKOVKA_1",
			48 => "ARTIKUL_NOMENKLATURY_DLYA_BITRIKS24",
			49 => "BRAND",
			50 => "EXPANDABLES_FILTER",
			51 => "LINK_SALE",
			52 => "POPUP_VIDEO",
			53 => "PODBORKI",
			54 => "ASSOCIATED_FILTER",
			55 => "LINK_REGION",
			56 => "STRANA_PROISKHOZHDENIYA_OSNOVA",
			57 => "VID_KOFE2",
			58 => "OBEM_ML_",
			59 => "OTDEL",
			60 => "PECHAT_ETIKETKI",
			61 => "PECHAT_ETIKETKI_2",
			62 => "EXPANDABLES",
			63 => "BIG_BLOCK",
			64 => "LINK_VACANCY",
			65 => "VIDEO_YOUTUBE",
			66 => "2104",
			67 => "COL_GROUP",
			68 => "LINK_NEWS",
			69 => "ASSOCIATED",
			70 => "HELP_TEXT",
			71 => "skorost_vrash",
			72 => "LINK_STAFF",
			73 => "LINK_BLOG",
			74 => "BCOUNTRY",
			75 => "SALE_TEXT",
			76 => "2033",
			77 => "FAVORIT_ITEM",
			78 => "SERVICES",
			79 => "2065",
			80 => "2052",
			81 => "2053",
			82 => "2089",
			83 => "2085",
			84 => "284",
			85 => "25",
			86 => "2101",
			87 => "2067",
			88 => "2054",
			89 => "283",
			90 => "2066",
			91 => "2084",
			92 => "TIP_KOFEMOLKI",
			93 => "2017",
			94 => "206",
			95 => "2100",
			96 => "315",
			97 => "2091",
			98 => "2026",
			99 => "307",
			100 => "GOD_RETSEPTA",
			101 => "GOD_IZGOTOVLENIYA",
			102 => "PROIZVODITELNOST_CHASHEK_V_DEN",
			103 => "VID_NOMENKLATURY_DLYA_BITRIKS24",
			104 => "POL_DLYA_BITRIKS24",
			105 => "DIAMETR_ZHERNOVOV_MM_",
			106 => "VMESTIMOST_BUNKERA",
			107 => "PROIZVODITELNOST_KG_DEN",
			108 => "SKOROST_POMOLA_SEK_DOZA",
			109 => "REKOMENDATSIYA",
			110 => "NAKLEYKA",
			111 => "OSTATOK_NOMENKLATURY_DLYA_BITRIKS24",
			112 => "DOLG_NAM_MY_PO_DANNYM_1S_DLYA_BITRIKS24",
			113 => "159",
			114 => "2083",
			115 => "COLOR_REF2",
			116 => "305",
			117 => "352",
			118 => "317",
			119 => "357",
			120 => "2102",
			121 => "318",
			122 => "349",
			123 => "327",
			124 => "370",
			125 => "336",
			126 => "2115",
			127 => "346",
			128 => "2120",
			129 => "363",
			130 => "320",
			131 => "325",
			132 => "2103",
			133 => "300",
			134 => "322",
			135 => "362",
			136 => "365",
			137 => "359",
			138 => "364",
			139 => "356",
			140 => "343",
			141 => "314",
			142 => "348",
			143 => "316",
			144 => "350",
			145 => "333",
			146 => "332",
			147 => "360",
			148 => "353",
			149 => "347",
			150 => "2114",
			151 => "301",
			152 => "323",
			153 => "324",
			154 => "355",
			155 => "304",
			156 => "358",
			157 => "319",
			158 => "344",
			159 => "328",
			160 => "338",
			161 => "2113",
			162 => "366",
			163 => "302",
			164 => "303",
			165 => "341",
			166 => "223",
			167 => "354",
			168 => "313",
			169 => "329",
			170 => "342",
			171 => "367",
			172 => "340",
			173 => "351",
			174 => "368",
			175 => "369",
			176 => "331",
			177 => "337",
			178 => "345",
			179 => "339",
			180 => "310",
			181 => "309",
			182 => "330",
			183 => "335",
			184 => "321",
			185 => "308",
			186 => "334",
			187 => "311",
			188 => "2132",
			189 => "SHUM",
			190 => "361",
			191 => "326",
			192 => "2090",
			193 => "2027",
			194 => "2098",
			195 => "2112",
			196 => "2122",
			197 => "221",
			198 => "24",
			199 => "2134",
			200 => "23",
			201 => "2049",
			202 => "22",
			203 => "2095",
			204 => "2044",
			205 => "162",
			206 => "207",
			207 => "220",
			208 => "2094",
			209 => "2092",
			210 => "2111",
			211 => "2133",
			212 => "2096",
			213 => "2086",
			214 => "285",
			215 => "2130",
			216 => "286",
			217 => "222",
			218 => "2121",
			219 => "2123",
			220 => "2124",
			221 => "2093",
			222 => "LINK_REVIEWS",
			223 => "312",
			224 => "3083",
			225 => "2055",
			226 => "2069",
			227 => "2062",
			228 => "2061",
    ];

$item['PICTURES'] = [];
$arSelect = Array("ID", "NAME", "CODE", "PREVIEW_TEXT", "PROPERTY_OPISANIE_DLYA_SAYTA", "PROPERTY_CML2_ARTICLE", "DETAIL_TEXT", "DETAIL_PICTURE");
$arFilter = Array("IBLOCK_ID"=>93, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["id"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();


    $productTitle = $arFields["NAME"];
    if ($arFields["DETAIL_PICTURE"]) $item['PICTURES'][0]['src'] = CFile::GetPath($arFields["DETAIL_PICTURE"]);
    $item["PREVIEW_TEXT"] = $arFields["PREVIEW_TEXT"];
    $item['DETAIL_TEXT'] = $arFields["DETAIL_TEXT"];
    $item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] = $arFields["PROPERTY_OPISANIE_DLYA_SAYTA_VALUE"];
    $item['PROPERTIES']['CML2_ARTICLE']['VALUE'] = $arFields["PROPERTY_CML2_ARTICLE_VALUE"];
}
    $arSelect = Array("ID", "NAME", "CODE");
    $arFilter = Array("IBLOCK_ID"=>93, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$_REQUEST["id"]);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext()) {
        $result[] = $ob;
    }

    if (!empty($result)) {
        foreach ($result as $key=>$item2) {
            $result[$key]["PROPERTIES"] = array();
            $arElementLink[$item2["ID"]] = &$result[$key];
            $elementsID[$key] = $item2["ID"];
        }
        $arPropFilter = array(
            'ID' => $elementsID,
            'IBLOCK_ID' => 93,
        );
        CIBlockElement::GetPropertyValuesArray($arElementLink, 93, $arPropFilter, []);
        foreach ($result as $key => $arItem) {
            if (!empty($arItem['PROPERTIES'])) {
                $arProperties = [];
                foreach ($arItem['PROPERTIES'] as $pCode => $arProperty) {
                    if ((is_array($arProperty['VALUE']) && !empty($arProperty['VALUE'])) OR (!is_array($arProperty['VALUE']) && strlen($arProperty['VALUE']) > 0)) {
                        $arProperties[$pCode] = \CIBlockFormatProperties::GetDisplayValue(array('ID' => $arItem['ID'], 'NAME' => $arItem['NAME']), $arProperty, '');
                    }
                }
                if (!empty($arProperties)) {
                    $result[$key]['PROPERTIES'] = $arProperties;
                }
            }
        }
    }
    if (!empty($result[0]['PROPERTIES'])) {
        foreach ($result[0]['PROPERTIES'] as $prop) {
            if (in_array($prop["CODE"], $arPropsShow) OR in_array($prop["ID"], $arPropsShow)) $item['DISPLAY_PROPERTIES'][] = $prop;
        }
    }
?>
<div>
    <div class="header">
        <div class="icon icon-cross close" onclick="$('.product-slide-info').removeClass('show')"></div>
    </div>
    <div class="body">
        <div class="slider">
            <? if ($item['PICTURES']){ ?>
            <div class="splide splide--slide splide--ltr splide--draggable is-active is-initialized" id="splide01" role="region" aria-roledescription="carousel">
                <div class="splide__track splide__track--slide splide__track--ltr splide__track--draggable" id="splide01-track" style="padding-left: 0px; padding-right: 0px;" aria-live="polite" aria-atomic="true">
                    <ul class="splide__list" id="splide01-list" role="presentation" style="transform: translateX(0px);">
        <? foreach ($item['PICTURES'] as $picture){ ?>
                        <li class="splide__slide is-active is-visible" id="splide01-slide01" role="tabpanel" aria-roledescription="slide" aria-label="1 of 1" style="width: calc(100%);">
                            <div class="slide">
                                <div class="content">
                                    <img src="<?= $picture['src'] ?>" data-webp-src="<?= $picture['src'] ?>" alt="<?= $productTitle ?>" title="<?= $productTitle ?>">
                                </div>
                            </div>
                        </li>
        <?}?>
                    </ul>
                </div>
                <ul class="splide__pagination splide__pagination--ltr" role="tablist" aria-label="Select a slide to show"><li role="presentation"><button class="splide__pagination__page is-active" type="button" role="tab" aria-controls="splide01-slide01" aria-label="Go to slide 1" aria-selected="true"></button></li></ul>
            </div>
            <?}?>
        </div>
        <div class="subslider">
            <div class="labels">
                <div class="label grey-noborder"><?= $item['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?></div>
                <? if ($item['LABEL']['ICON']): ?>
                    <div class="label <?= $item['LABEL']['CLASS'] ?>">
                        <div class="icon icon-<?= $item['LABEL']['ICON'] ?>"></div>
                        <span><?= $item['LABEL']['TEXT'] ?></span>
                    </div>
                <? endif ?>
            </div>
            <div class="favourite detail-favorite" id="<?= $itemIds['FAVORITE_BTN'] ?>_detail"></div>
        </div>
        <h3><?= $productTitle ?></h3>
        <div class="subtitle"><?= $item['PREVIEW_TEXT'] ?></div>
        <div class="characteristics">
            <? if (!empty($item['DISPLAY_PROPERTIES'])): ?>
                <h4 class="title">Характеристики:</h4>
                <div class="text-items">
                    <? foreach ($item['DISPLAY_PROPERTIES'] as $displayProperty): ?>
                        <div class="item">
                            <div class="name"><?= $displayProperty['NAME'] ?>:</div>
                            <div class="value"><?= (is_array($displayProperty['DISPLAY_VALUE'])
                                    ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                    : $displayProperty['DISPLAY_VALUE']) ?></div>
                        </div>
                    <? endforeach ?>
                </div>
            <? endif ?>
            <? if ($item['RANGES']): ?>
                <div class="progress-items">
                    <? foreach ($item['RANGES'] as $rangeItem): ?>
                        <div class="item">
                            <div class="progress" style="--val: <?= $rangeItem['VALUE'] ?>%"></div>
                            <div class="text"><?= $rangeItem['NAME'] ?></div>
                        </div>
                    <? endforeach ?>
                </div>
            <? endif ?>
        </div>
        <? if ($item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] || $item['DETAIL_TEXT']): ?>
            <div class="description">
                <div class=" text-content">
                    <h4 class="title">Описание:</h4>
                    <?= $item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE'] ?htmlspecialcharsBack($item['PROPERTIES']['OPISANIE_DLYA_SAYTA']['VALUE']): $item['DETAIL_TEXT'] ?>
                </div>
            </div>
        <? endif ?>
    </div>
</div>
<?}?>