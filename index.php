<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Легенда Чая B2B"); ?>
<div class="three-cols-table-container">
    <div class="left">
    </div>
    <div class="mid">

        <div class="search">
            <?
            if (isset($_REQUEST["q"]) && isset($_REQUEST["ajax_call"]) && $_REQUEST["ajax_call"] === "y") $APPLICATION->RestartBuffer();
            $arSearchElementsTop = $APPLICATION->IncludeComponent(
                "bitrix:search.page",
                "top_catalog",
                array(
                    'STR_SEARCH' => '/catalog/chay/belyy/',
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
            ?>
        </div>

        <!--Заказы-->
        <? if ($USER->isAuthorized()): ?>
            <?

            $APPLICATION->IncludeComponent("bitrix:news.list", "history_list", array(
                "COMPONENT_TEMPLATE" => ".default",
                "IBLOCK_TYPE" => "aspro_max_content",    // Тип информационного блока (используется только для проверки)
                "IBLOCK_ID" => "128",    // Код информационного блока
                "NEWS_COUNT" => "20",    // Количество новостей на странице
                "SORT_BY1" => "SORT",    // Поле для первой сортировки новостей
                "SORT_ORDER1" => "ASC",    // Направление для первой сортировки новостей
                "SORT_BY2" => "ACTIVE_FROM",    // Поле для второй сортировки новостей
                "SORT_ORDER2" => "ASC",    // Направление для второй сортировки новостей
                "FILTER_NAME" => "",    // Фильтр
                "FIELD_CODE" => array(    // Поля
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE" => array(    // Свойства
                    0 => "LINK",
                    1 => "",
                ),
                "CHECK_DATES" => "Y",    // Показывать только активные на данный момент элементы
                "DETAIL_URL" => "",    // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
                "AJAX_MODE" => "N",    // Включить режим AJAX
                "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
                "AJAX_OPTION_STYLE" => "N",    // Включить подгрузку стилей
                "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
                "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
                "CACHE_TYPE" => "A",    // Тип кеширования
                "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
                "CACHE_GROUPS" => "Y",    // Учитывать права доступа
                "PREVIEW_TRUNCATE_LEN" => "",    // Максимальная длина анонса для вывода (только для типа текст)
                "ACTIVE_DATE_FORMAT" => "",    // Формат показа даты
                "SET_TITLE" => "N",    // Устанавливать заголовок страницы
                "SET_BROWSER_TITLE" => "Y",    // Устанавливать заголовок окна браузера
                "SET_META_KEYWORDS" => "N",    // Устанавливать ключевые слова страницы
                "SET_META_DESCRIPTION" => "N",    // Устанавливать описание страницы
                "SET_LAST_MODIFIED" => "N",    // Устанавливать в заголовках ответа время модификации страницы
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",    // Включать инфоблок в цепочку навигации
                "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",    // Скрывать ссылку, если нет детального описания
                "PARENT_SECTION" => "",    // ID раздела
                "PARENT_SECTION_CODE" => "",    // Код раздела
                "INCLUDE_SUBSECTIONS" => "N",    // Показывать элементы подразделов раздела
                "STRICT_SECTION_CHECK" => "N",    // Строгая проверка раздела для показа списка
                "DISPLAY_DATE" => "N",    // Выводить дату элемента
                "DISPLAY_NAME" => "Y",    // Выводить название элемента
                "DISPLAY_PICTURE" => "Y",    // Выводить изображение для анонса
                "DISPLAY_PREVIEW_TEXT" => "Y",    // Выводить текст анонса
                "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
                "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
                "PAGER_TEMPLATE" => ".default",    // Шаблон постраничной навигации
                "DISPLAY_TOP_PAGER" => "N",    // Выводить над списком
                "DISPLAY_BOTTOM_PAGER" => "N",    // Выводить под списком
                "PAGER_TITLE" => "Новости",    // Название категорий
                "PAGER_SHOW_ALWAYS" => "N",    // Выводить всегда
                "PAGER_DESC_NUMBERING" => "N",    // Использовать обратную навигацию
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",    // Время кеширования страниц для обратной навигации
                "PAGER_SHOW_ALL" => "N",    // Показывать ссылку "Все"
                "PAGER_BASE_LINK_ENABLE" => "N",    // Включить обработку ссылок
                "SET_STATUS_404" => "N",    // Устанавливать статус 404
                "SHOW_404" => "N",    // Показ специальной страницы
                "MESSAGE_404" => "",    // Сообщение для показа (по умолчанию из компонента)
            ),
                false
            );


            $APPLICATION->IncludeComponent(
                "bitrix:sale.personal.order.list",
                "main",
                array(
                    "COMPONENT_TEMPLATE" => "main",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "360000",
                    "CACHE_GROUPS" => "N",
                    "PATH_TO_DETAIL" => "/personal/orders/#ID#",
                    "PATH_TO_COPY" => "",
                    "PATH_TO_CANCEL" => "",
                    "PATH_TO_PAYMENT" => "/personal/order/payment/",
                    "PATH_TO_BASKET" => "",
                    "PATH_TO_CATALOG" => "/catalog/",
                    "ORDERS_PER_PAGE" => "20",
                    "ID" => $ID,
                    "DISALLOW_CANCEL" => "N",
                    "SET_TITLE" => "N",
                    "SAVE_IN_SESSION" => "N",
                    "NAV_TEMPLATE" => "",
                    "HISTORIC_STATUSES" => array(
                        0 => "F",
                    ),
                    "RESTRICT_CHANGE_PAYSYSTEM" => array(
                        0 => "0",
                    ),
                    "REFRESH_PRICES" => "N",
                    "DEFAULT_SORT" => "ID",
                    "ALLOW_INNER" => "N",
                    "ONLY_INNER_FULL" => "N",
                    "STATUS_COLOR_D" => "gray",
                    "STATUS_COLOR_F" => "gray",
                    "STATUS_COLOR_N" => "green",
                    "STATUS_COLOR_P" => "yellow",
                    "STATUS_COLOR_S" => "gray",
                    "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO"
                ),
                false
            ); ?>
        <? endif ?>
        <!--Новости-->
        <? $APPLICATION->IncludeComponent("bitrix:news.list", "news-index", array(
            "COMPONENT_TEMPLATE" => ".default",
            "IBLOCK_TYPE" => "aspro_max_content",    // Тип информационного блока (используется только для проверки)
            "IBLOCK_ID" => "15",    // Код информационного блока
            "NEWS_COUNT" => "3",    // Количество новостей на странице
            "SORT_BY1" => "ACTIVE_FROM",    // Поле для первой сортировки новостей
            "SORT_ORDER1" => "DESC",    // Направление для первой сортировки новостей
            "SORT_BY2" => "SORT",    // Поле для второй сортировки новостей
            "SORT_ORDER2" => "ASC",    // Направление для второй сортировки новостей
            "FILTER_NAME" => "",    // Фильтр
            "FIELD_CODE" => array(    // Поля
                0 => "",
                1 => "",
            ),
            "PROPERTY_CODE" => array(    // Свойства
                0 => "",
                1 => "",
            ),
            "CHECK_DATES" => "Y",    // Показывать только активные на данный момент элементы
            "DETAIL_URL" => "",    // URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
            "AJAX_MODE" => "N",    // Включить режим AJAX
            "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
            "AJAX_OPTION_STYLE" => "N",    // Включить подгрузку стилей
            "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
            "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
            "CACHE_TYPE" => "A",    // Тип кеширования
            "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
            "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
            "CACHE_GROUPS" => "Y",    // Учитывать права доступа
            "PREVIEW_TRUNCATE_LEN" => "",    // Максимальная длина анонса для вывода (только для типа текст)
            "ACTIVE_DATE_FORMAT" => "d.m.Y",    // Формат показа даты
            "SET_TITLE" => "N",    // Устанавливать заголовок страницы
            "SET_BROWSER_TITLE" => "N",    // Устанавливать заголовок окна браузера
            "SET_META_KEYWORDS" => "N",    // Устанавливать ключевые слова страницы
            "SET_META_DESCRIPTION" => "N",    // Устанавливать описание страницы
            "SET_LAST_MODIFIED" => "N",    // Устанавливать в заголовках ответа время модификации страницы
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",    // Включать инфоблок в цепочку навигации
            "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",    // Скрывать ссылку, если нет детального описания
            "PARENT_SECTION" => "",    // ID раздела
            "PARENT_SECTION_CODE" => "",    // Код раздела
            "INCLUDE_SUBSECTIONS" => "N",    // Показывать элементы подразделов раздела
            "STRICT_SECTION_CHECK" => "N",    // Строгая проверка раздела для показа списка
            "DISPLAY_DATE" => "Y",    // Выводить дату элемента
            "DISPLAY_NAME" => "Y",    // Выводить название элемента
            "DISPLAY_PICTURE" => "Y",    // Выводить изображение для анонса
            "DISPLAY_PREVIEW_TEXT" => "Y",    // Выводить текст анонса
            "COMPOSITE_FRAME_MODE" => "A",    // Голосование шаблона компонента по умолчанию
            "COMPOSITE_FRAME_TYPE" => "AUTO",    // Содержимое компонента
            "PAGER_TEMPLATE" => ".default",    // Шаблон постраничной навигации
            "DISPLAY_TOP_PAGER" => "N",    // Выводить над списком
            "DISPLAY_BOTTOM_PAGER" => "N",    // Выводить под списком
            "PAGER_TITLE" => "Новости",    // Название категорий
            "PAGER_SHOW_ALWAYS" => "N",    // Выводить всегда
            "PAGER_DESC_NUMBERING" => "N",    // Использовать обратную навигацию
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",    // Время кеширования страниц для обратной навигации
            "PAGER_SHOW_ALL" => "N",    // Показывать ссылку "Все"
            "PAGER_BASE_LINK_ENABLE" => "N",    // Включить обработку ссылок
            "SET_STATUS_404" => "N",    // Устанавливать статус 404
            "SHOW_404" => "N",    // Показ специальной страницы
            "MESSAGE_404" => "",    // Сообщение для показа (по умолчанию из компонента)
        ),
            false
        ); ?>
        <!--Новинки-->
        <? /*
        global $newFilter;
        $newFilter = ['PROPERTY_HIT_VALUE' => 'Новинка'];

        $baseFilter = [
            'IBLOCK_ID' => CATALOG_IBLOCK_ID,
            'SECTION_ACTIVE' => 'Y',
            'SECTION_GLOBAL_ACTIVE' => 'Y',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'ACTIVE' => 'Y'
        ];
        $filter = array_merge($newFilter, $baseFilter);
        $sections = \Webfly\Helper\Helper::getCatalogSections($filter);
        ?>
        <? $APPLICATION->IncludeComponent('bitrix:catalog.section', '', array(
            'IBLOCK_TYPE' => '1c_catalog',
            'IBLOCK_ID' => '93',
            'ELEMENT_SORT_FIELD' => 'NAME',
            'ELEMENT_SORT_ORDER' => 'asc',
            'ELEMENT_SORT_FIELD2' => 'sort',
            'ELEMENT_SORT_ORDER2' => 'asc',
            'PROPERTY_CODE' =>
                array(
                    0 => 'HIT',
                    1 => 'CML2_ARTICLE',
                    2 => '',
                ),
            'PROPERTY_CODE_MOBILE' =>
                array(),
            'META_KEYWORDS' => '-',
            'META_DESCRIPTION' => '-',
            'BROWSER_TITLE' => '-',
            'SET_LAST_MODIFIED' => 'N',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'BASKET_URL' => '/personal/cart/',
            'ACTION_VARIABLE' => 'action',
            'PRODUCT_ID_VARIABLE' => 'id',
            'SECTION_ID_VARIABLE' => 'SECTION_ID',
            'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
            'PRODUCT_PROPS_VARIABLE' => 'prop',
            'FILTER_NAME' => 'newFilter',
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => '36000000',
            'CACHE_FILTER' => 'Y',
            'CACHE_GROUPS' => 'Y',
            'SET_TITLE' => 'N',
            'MESSAGE_404' => '',
            'SET_STATUS_404' => 'Y',
            'SHOW_404' => 'N',
            'FILE_404' => '',
            'DISPLAY_COMPARE' => 'N',
            'PAGE_ELEMENT_COUNT' => '10',
            'LINE_ELEMENT_COUNT' => '3',
            'PRICE_CODE' =>
                array(
                    0 => 'ОПТОВЫЙ КАБИНЕТ',
                ),
            'USE_PRICE_COUNT' => 'N',
            'SHOW_PRICE_COUNT' => '1',
            'PRICE_VAT_INCLUDE' => 'Y',
            'USE_PRODUCT_QUANTITY' => 'Y',
            'ADD_PROPERTIES_TO_BASKET' => 'Y',
            'PARTIAL_PRODUCT_PROPERTIES' => 'Y',
            'PRODUCT_PROPERTIES' =>
                array(),
            'DISPLAY_TOP_PAGER' => 'N',
            'DISPLAY_BOTTOM_PAGER' => 'Y',
            'PAGER_TITLE' => 'Товары',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => 'round',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000000',
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_BASE_LINK' => NULL,
            'PAGER_PARAMS_NAME' => NULL,
            'LAZY_LOAD' => 'Y',
            'MESS_BTN_LAZY_LOAD' => 'Показать ещё',
            'LOAD_ON_SCROLL' => 'N',
            'OFFERS_CART_PROPERTIES' =>
                array(
                    0 => 'UPAKOVKA',
                    1 => 'POMOL',
                    2 => 'OBEM',
                ),
            'OFFERS_FIELD_CODE' =>
                array(
                    0 => '',
                    1 => '',
                ),
            'OFFERS_PROPERTY_CODE' =>
                array(
                    0 => 'UPAKOVKA',
                    1 => 'POMOL',
                    2 => 'OBEM',
                ),
            'OFFERS_SORT_FIELD' => 'sort',
            'OFFERS_SORT_ORDER' => 'desc',
            'OFFERS_SORT_FIELD2' => 'id',
            'OFFERS_SORT_ORDER2' => 'desc',
            'OFFERS_LIMIT' => '0',
            'SECTION_ID' => current($sections)['ID'],
            'SECTION_CODE' => current($sections)['CODE'],
            'SECTION_URL' => '/catalog/#SECTION_CODE#/',
            'DETAIL_URL' => '/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
            'USE_MAIN_ELEMENT_SECTION' => 'N',
            'CONVERT_CURRENCY' => 'Y',
            'CURRENCY_ID' => 'RUB',
            'HIDE_NOT_AVAILABLE' => 'L',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'LABEL_PROP' =>
                array(),
            'LABEL_PROP_MOBILE' => '',
            'LABEL_PROP_POSITION' => 'top-left',
            'ADD_PICT_PROP' => 'MORE_PHOTO',
            'PRODUCT_DISPLAY_MODE' => 'Y',
            'PRODUCT_BLOCKS_ORDER' => 'price,props,sku,quantityLimit,quantity,buttons',
            'PRODUCT_ROW_VARIANTS' => '[{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false},{\'VARIANT\':\'2\',\'BIG_DATA\':false}]',
            'ENLARGE_PRODUCT' => 'STRICT',
            'ENLARGE_PROP' => '',
            'SHOW_SLIDER' => 'N',
            'SLIDER_INTERVAL' => '3000',
            'SLIDER_PROGRESS' => 'N',
            'OFFER_ADD_PICT_PROP' => 'MORE_PHOTO',
            'OFFER_TREE_PROPS' =>
                array(
                    0 => 'UPAKOVKA',
                    1 => 'POMOL',
                    2 => 'OBEM',
                ),
            'PRODUCT_SUBSCRIPTION' => 'Y',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'DISCOUNT_PERCENT_POSITION' => 'bottom-right',
            'SHOW_OLD_PRICE' => 'N',
            'SHOW_MAX_QUANTITY' => 'N',
            'MESS_SHOW_MAX_QUANTITY' => '',
            'RELATIVE_QUANTITY_FACTOR' => '',
            'MESS_RELATIVE_QUANTITY_MANY' => '',
            'MESS_RELATIVE_QUANTITY_FEW' => '',
            'MESS_BTN_BUY' => 'Купить',
            'MESS_BTN_ADD_TO_BASKET' => 'В корзину',
            'MESS_BTN_SUBSCRIBE' => 'Уведомить о поступлении',
            'MESS_BTN_DETAIL' => 'Подробнее',
            'MESS_NOT_AVAILABLE' => 'Нет в наличии',
            'MESS_BTN_COMPARE' => 'Сравнение',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'DATA_LAYER_NAME' => '',
            'BRAND_PROPERTY' => '',
            'TEMPLATE_THEME' => 'site',
            'ADD_SECTIONS_CHAIN' => 'N',
            'ADD_TO_BASKET_ACTION' => 'ADD',
            'SHOW_CLOSE_POPUP' => 'N',
            'COMPARE_PATH' => '/catalog/compare/',
            'COMPARE_NAME' => NULL,
            'USE_COMPARE_LIST' => 'Y',
            'BACKGROUND_IMAGE' => '-',
            'COMPATIBLE_MODE' => 'N',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
            'SECTIONS' => $sections,
            'NEW' => 'Y'
        )); */ ?>
    </div>
    <div class="right">
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
