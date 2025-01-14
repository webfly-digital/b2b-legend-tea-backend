<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 *  _________________________________________________________________________
 * |    Attention!
 * |    The following comments are for system use
 * |    and are required for the component to work correctly in ajax mode:
 * |    <!-- items-container -->
 * |    <!-- pagination-container -->
 * |    <!-- component-end -->
 */

$this->setFrameMode(true);
$this->addExternalJs($templateFolder . '/subscribe.js');


if (!empty($arResult['NAV_RESULT'])) {
    $navParams = array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
} else {
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

$showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));


$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD'] ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');

$generalParams = array(
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
    'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
    'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
    'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
    'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
    'LABEL_POSITION_CLASS' => $labelPositionClass,
    'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
    'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
    '~BASKET_URL' => $arParams['~BASKET_URL'],
    '~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
    '~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
    '~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
    'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
    'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
    'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
    'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
    'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
    "POMOL" => $arParams["POMOL"],
    "VIEW" => $arParams["VIEW"],
    "DELIVERY_TIME" => $arParams['DELIVERY_TIME'],
    'GENERATE_PDF' => $arParams['GENERATE_PDF'],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "SEARCH" => $arParams["SEARCH"],
    'USER_ID' => $arParams["USER_ID"],
    'SECTION_MAIN' => $arParams["SECTION_MAIN"],
);

$obName = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-' . $navParams['NavNum'];

$needOpen = count($arParams["SECTIONS"]) == 1 ? false : true; //только первый открываем
$spoiler = count($arParams["SECTIONS"]) == 1 ? false : true;


?>
<? if (empty($arResult['ITEMS']) && $arParams['SEARCH'] == 'Y'): ?>
    <h4>Сожалеем, но ничего не найдено :(</h4>
<? endif; ?>


<style> .pagination a.item .num {
        color: #1c1e1f;
    }</style>


<? if ($arParams['NEW']): ?>
<section>
    <div class="section-title">
        <h2>Новинки</h2>
    </div>
    <? endif ?>
    <div class="product-table-group" data-entity="<?= $containerName ?>">
        <? if (!empty($arResult['SECTIONS'])) {
            $areaIds = [];
            foreach ($arResult['SECTIONS'] as $sectionId => $sectionData) { ?>
                <div class="product-table-wrapper <? if ($spoiler): ?>spoiler<? endif ?> <?= $needOpen ? 'open-spoiler' : '' ?>"
                     data-spoiler-default-state="<?= $needOpen ? 'opened' : '' ?>"
                     data-sid="<?= $sectionId ?>"
                     data-code="<?= $sectionData['CODE'] ?>" id="spoiler-<?= $sectionId ?>"
                     data-page="1">
                    <div class="product-table-title toggler">
                        <h3><?= $sectionData['NAME'] ?></h3>
                        <? if ($spoiler): ?>
                            <div class="icon-link">
                                <span class="initial">Раскрыть</span>
                                <span class="expanded">Скрыть</span>
                                <div class="icon"></div>
                            </div>
                        <? endif ?>
                    </div>
                    <div class="product-table catalog-table content <?= $arResult['ADDITIONAL'][$sectionId]['CLASS'] ?: '' ?>">
                        <div class="product-table-header">
                            <div class="product-table-row">
                                <div class="left">
                                    <div class="product-table-cell"><span>Информация</span></div>
                                </div>
                                <div class="right">
                                    <? if ($arResult['COLUMNS'][$sectionId]):
                                        foreach ($arResult['COLUMNS'][$sectionId] as $column):?>
                                            <div class="product-table-cell"><span><?= $column['NAME'] ?></span>
                                            </div>
                                        <?endforeach;
                                    endif ?>
                                    <div class="product-table-cell"><span>Цена</span></div>
                                    <div class="product-table-cell"><span>Количество</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="product-table-body" data-entity="items-row">
                            <!-- items-container -->
                            <?
                            if ($arResult['ITEMS'][$sectionId]):
                                $arResult['JS_ITEMS'] = [];
                                foreach ($arResult['ITEMS'][$sectionId] as $item) {
                                    $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
                                    $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
                                    $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
                                    $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                                    $arResult['JS_ITEMS'][$item['ID']] = $areaIds[$item['ID']];

                                    $tmp = '';
                                    global $USER;
                                    if ($USER->GetID() == 2389) {
                                        $tmp = 'admin';
                                    }



                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.item',
                                        $tmp,
                                        array(
                                            'RESULT' => array(
                                                'ITEM' => $item,
                                                'AREA_ID' => $areaIds[$item['ID']],
                                                'TYPE' => 'CARD',
                                                'BIG_LABEL' => 'N',
                                                'BIG_DISCOUNT_PERCENT' => 'N',
                                                'BIG_BUTTONS' => 'Y',
                                                'SCALABLE' => 'N'
                                            ),
                                            'PARAMS' => $generalParams
                                                + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                                        ),
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                }
                                $arResult['JS_OBJECT'] = $obName;
                                $component->SetResultCacheKeys(array("JS_ITEMS", 'JS_OBJECT'));
                            endif ?>
                            <!-- items-container -->
                        </div>

                        <? if ($showBottomPager && $spoiler == false) { ?>
                            <div data-pagination-num="<?= $navParams['NavNum'] ?>">
                                <!-- pagination-container -->
                                <?= $arResult['NAV_STRING'] ?>
                                <!-- pagination-container -->
                            </div>
                        <? } ?>
                    </div>
                </div>
                <?
                if ($needOpen) $needOpen = false;
            }
            unset($generalParams, $rowItems);
        } ?>
        <? if ($arParams['SEARCH'] == 'Y' && !empty($arResult['ITEMS'])) {
            $areaIds = []; ?>
            <div class="product-table-wrapper" data-page="1">
                <div class="product-table catalog-table">
                    <div class="product-table-header">
                        <div class="product-table-row">
                            <div class="left">
                                <div class="product-table-cell"><span>Информация</span></div>
                            </div>
                            <div class="right">
                                <? if ($arResult['COLUMNS_TITLE']):
                                    foreach ($arResult['COLUMNS_TITLE'] as $column):?>
                                        <div class="product-table-cell"><span><?= $column['NAME'] ?></span>
                                        </div>
                                    <?endforeach;
                                endif ?>
                                <div class="product-table-cell"><span>Цена</span></div>
                                <div class="product-table-cell"><span>Количество</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="product-table-body" data-entity="items-row">
                        <!-- items-container -->
                        <? if ($arResult['ITEMS']) {
                            $arResult['JS_ITEMS'] = [];
                            foreach ($arResult['ITEMS'] as $item) {
                                $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
                                $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
                                $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
                                $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                                $arResult['JS_ITEMS'][$item['ID']] = $areaIds[$item['ID']];

                                $tmp = '';
                                global $USER;
                                if ($USER->GetID() == 2389) {
                                    $tmp = 'admin';
                                }

                             

                                $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.item',
                                    $tmp,
                                    array(
                                        'RESULT' => array(
                                            'ITEM' => $item,
                                            'AREA_ID' => $areaIds[$item['ID']],
                                            'TYPE' => 'CARD',
                                            'BIG_LABEL' => 'N',
                                            'BIG_DISCOUNT_PERCENT' => 'N',
                                            'BIG_BUTTONS' => 'Y',
                                            'SCALABLE' => 'N'
                                        ),
                                        'PARAMS' => $generalParams
                                            + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );

                            }
                            $arResult['JS_OBJECT'] = $obName;
                            $component->SetResultCacheKeys(array("JS_ITEMS", 'JS_OBJECT'));
                        } ?>
                        <!-- items-container -->
                    </div>

                    <? if ($showBottomPager) { ?>
                        <div data-pagination-num="<?= $navParams['NavNum'] ?>">
                            <!-- pagination-container -->
                            <?= $arResult['NAV_STRING'] ?>
                            <!-- pagination-container -->
                        </div>
                    <? } ?>
                </div>
            </div>
            <?
            unset($generalParams, $rowItems);
        } ?>


        <?
        if (!$arResult['ITEMS']) {
            // load css for bigData/deferred load
            $APPLICATION->IncludeComponent(
                'bitrix:catalog.item',
                '',
                array(),
                $component,
                array('HIDE_ICONS' => 'Y')
            );
        }
        ?>
    </div>
    <? if ($arParams['NEW']): ?>
</section>
<? endif ?>



<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>
<script>
    BX.message({
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
        BASKET_URL: '<?=$arParams['BASKET_URL']?>',
        ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
        BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
        COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
        BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
        BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
        SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
    });
    var <?=$obName?> =
        new JCCatalogSectionComponent({
            siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
            componentPath: '<?=CUtil::JSEscape($componentPath)?>',
            navParams: <?=CUtil::PhpToJSObject($navParams)?>,
            lazyLoad: !!'<?=$showLazyLoad?>',
            template: '<?=CUtil::JSEscape($signedTemplate)?>',
            ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'])?>',
            parameters: '<?=CUtil::JSEscape($signedParams)?>',
            container: '<?=$containerName?>',
            sectionAjaxPath: '<?=CUtil::JSEscape(SITE_TEMPLATE_PATH)?>/ajax/catalog.section.php'
        });
</script>
<!-- component-end -->
