<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if ($arParams["MAIN_CHAIN_NAME"] <> '') {
    $APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);

$availablePages = array();


if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_PRIVATE'],
        "name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
        "icon" => '<i class="fa fa-user-secret"></i>'
    );
}


$availablePages[] = array(
    "path" => '/personal/password/',
    "name" => 'Сменить пароль',
    "icon" => '<i class="fa fa-user-secret"></i>'
);

//$availablePages[] = array(
//    "path" => '/personal/password/',
//    "name" => 'Сертификаты',
//    "icon" => '<i class="fa fa-user-secret"></i>'
//);

if ($arParams['SHOW_ORDER_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_ORDERS'],
        "name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
        "icon" => '<i class="fa fa-calculator"></i>'
    );
}
if ($arParams['SHOW_PROFILE_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_PROFILE'],
        "name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
        "icon" => '<i class="fa fa-list-ol"></i>'
    );
}

//if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y') {
//    $availablePages[] = array(
//        "path" => $arResult['PATH_TO_SUBSCRIBE'],
//        "name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
//        "icon" => '<i class="fa fa-envelope"></i>'
//    );
//}

$availablePages[] = array(
    "path" => '/news/',
    "name" => 'Новости',
    "icon" => '<i class="fa fa-user-secret"></i>'
);

$availablePages[] = array(
    "path" => '/rules/',
    "name" => 'Условия работы',
    "icon" => '<i class="fa fa-user-secret"></i>'
);

if ($arParams['SHOW_CONTACT_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arParams['PATH_TO_CONTACT'],
        "name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
        "icon" => '<i class="fa fa-info-circle"></i>'
    );
}

$availablePages[] = array(
    "path" => '/personal/?logout=yes',
    "name" => 'Выйти',
    "icon" => '<i class="fa fa-user-secret"></i>'
);

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList) {
    foreach ($customPagesList as $page) {
        $availablePages[] = array(
            "path" => $page[0],
            "name" => $page[1],
            "icon" => (mb_strlen($page[2])) ? '<i class="fa ' . htmlspecialcharsbx($page[2]) . '"></i>' : ""
        );
    }
}

if (empty($availablePages)) {
    ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
} else {
    ?>
    <div class="container-size-3">
        <h1 class="mb-3">Кабинет</h1>
        <? foreach ($availablePages as $blockElement) { ?>
            <a class="text-grey2" href="<?= $blockElement['path'] ?>">
                <h5 class="mt-1">
                    <?= htmlspecialcharsbx($blockElement['name']) ?>
                </h5>
            </a>
        <? } ?>
    </div>
    <?
}
?>
