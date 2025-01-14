<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$context = Application::getInstance()->getContext();
$request = $context->getRequest();
/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] === 'Y') {
    $basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
} else {
    $basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}
$dayDeclension = new Bitrix\Main\Grid\Declension('день', 'дня', 'дней');
$day = $dayDeclension->get($arParams['DELIVERY_TIME']);

global $USER;

$sectionOneLevel = false;
if ($arResult["VARIABLES"]["SECTION_ID"]) {
    $res = \CIBlockSection::getList(['NAME' => 'asc'], ['ID' => $arResult["VARIABLES"]["SECTION_ID"], 'IBLOCK_ID' => $arParams['IBLOCK_ID'], 'DEPTH_LEVEL' => 1], false, ['ID'], false);
    while ($ob = $res->fetch()) {
        $sectionOneLevel = true;
    }
}

?>

<div class="three-cols-table-container">
    <div class="left">
    </div>
    <div class="mid">
        <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(),
            false
        ); ?>
        <div class="catalog">
            <? if ($sectionOneLevel): ?>
                <?
                $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog", array(
                    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "",    // Дополнительный фильтр для подсчета количества элементов в разделе
                    "VIEW_MODE" => "LIST",    // Вид списка подразделов
                    "SHOW_PARENT_NAME" => "Y",    // Показывать название раздела
                    "IBLOCK_TYPE" => "",    // Тип инфоблока
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],    // Инфоблок
                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],    // ID раздела
                    "SECTION_CODE" => "",    // Код раздела
                    "SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
                    "COUNT_ELEMENTS" => "Y",    // Показывать количество элементов в разделе
                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",    // Показывать количество
                    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "Y",    // Скрывать разделы с нулевым количеством элементов
                    "TOP_DEPTH" => "2",    // Максимальная отображаемая глубина разделов
                    "SECTION_FIELDS" => "",    // Поля разделов
                    "SECTION_USER_FIELDS" => "",    // Свойства разделов
                    "ADD_SECTIONS_CHAIN" => "Y",    // Включать раздел в цепочку навигации
                    "CACHE_TYPE" => "A",    // Тип кеширования
                    "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                    "CACHE_NOTES" => "",
                    "CACHE_GROUPS" => "Y",    // Учитывать права доступа
                    'SHOW_NEW' => 'N'
                ),
                    false
                ); ?>
            <? else: ?>
            <div class="catalog-header">
                <div class="manual-notify">
                    <p>Как работать с фильтрами, сортировкой и каталогом? <br><a target="_blank" href="/instruction/">Читать
                            инструкцию</a></p>
                    <div class="icon icon-cross close" style="background: #979797;"></div>
                </div>
                <div class="middle">
                    <? include_once 'order.php'; ?>
                    <? include_once 'prices.php'; ?>
                    <? include_once 'search_form.php'; ?>
                    <? include_once 'filter.php'; ?>
                    <? include_once 'view.php'; ?>
                    <? if (!$searchInitialised) include_once 'pomol.php'; //если етсь поиск, то не отображаем помол?>
                </div>

                <? //$APPLICATION->ShowViewContent('FILTER_RESULTS'); ?>
            </div>
        </div>
        <div class="catalog-body">
            <?
            if ($searchInitialised && empty($arSearchElementsTop)) { // резултат из верхнего поиска?>
                <h4>Сожалеем, но ничего не найдено :(</h4>
            <? } else { ?>
                <? include_once 'search.php'; ?>
                <? include_once 'items.php'; ?>
            <? } ?>
        </div>
    <? endif ?>
    </div>
</div>
<div class="right">
</div>
</div>
<div class="checkout-warn" style="display: none ; width: 320px; border: 2px solid #f2f2f2;" id="popupStock">
    <span>Товар со статусом «под заказ» <br>Срок поставки до <?= $arParams['DELIVERY_TIME'] . ' ' . $day ?> </span>
    <button class="button-full mt-2">Закрыть</button>
</div>


