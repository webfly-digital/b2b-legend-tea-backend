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

use Bitrix\Main\Loader;

$this->setFrameMode(true);
?>

<div class="three-cols-table-container">
    <div class="left">
    </div>
    <div class="mid">
        <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(),
            false
        ); ?>
        <div class="catalog">
            <?
            $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog", array(
                "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "",    // Дополнительный фильтр для подсчета количества элементов в разделе
                "VIEW_MODE" => "LIST",    // Вид списка подразделов
                "SHOW_PARENT_NAME" => "Y",    // Показывать название раздела
                "IBLOCK_TYPE" => "",    // Тип инфоблока
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],    // Инфоблок
                "SECTION_ID" => $_REQUEST["SECTION_ID"],    // ID раздела
                "SECTION_CODE" => "",    // Код раздела
                "SECTION_URL" => "",    // URL, ведущий на страницу с содержимым раздела
                "COUNT_ELEMENTS" => "Y",    // Показывать количество элементов в разделе
                "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",    // Показывать количество
                "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "Y",    // Скрывать разделы с нулевым количеством элементов
                "TOP_DEPTH" => "2",    // Максимальная отображаемая глубина разделов
                "SECTION_FIELDS" => "",    // Поля разделов
                "SECTION_USER_FIELDS" => "",    // Свойства разделов
                "ADD_SECTIONS_CHAIN" => "",    // Включать раздел в цепочку навигации
                "CACHE_TYPE" => "A",    // Тип кеширования
                "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                "CACHE_NOTES" => "",
                "CACHE_GROUPS" => "Y",    // Учитывать права доступа
                'SHOW_NEW' => 'Y'
            ),
                false
            ); ?>
        </div>
    </div>
    <div class="right">
    </div>
</div>
