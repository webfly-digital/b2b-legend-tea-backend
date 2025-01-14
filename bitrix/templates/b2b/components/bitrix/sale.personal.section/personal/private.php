<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

if ($arParams['SHOW_PRIVATE_PAGE'] !== 'Y' && $arParams['USE_PRIVATE_PAGE_TO_AUTH'] !== 'Y') {
    LocalRedirect($arParams['SEF_FOLDER']);
}

if ($arParams['SET_TITLE'] == 'Y') {
    $APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PRIVATE"));
}


if (!$USER->IsAuthorized() || $arResult['SHOW_LOGIN_FORM'] === 'Y') {
    if ($arParams['USE_PRIVATE_PAGE_TO_AUTH'] !== 'Y') {
        ob_start();
        $APPLICATION->AuthForm('', false, false, 'N', false);
        $authForm = ob_get_clean();
    } else {
        if ($arResult['SHOW_FORGOT_PASSWORD_FORM'] === 'Y') {
            ob_start();
            $APPLICATION->IncludeComponent(
                'bitrix:main.auth.forgotpasswd',
                '.default',
                array(
                    'AUTH_AUTH_URL' => $arResult['PATH_TO_PRIVATE'],
//					'AUTH_REGISTER_URL' => 'register.php',
                ),
                false
            );
            $authForm = ob_get_clean();
        } elseif ($arResult['SHOW_CHANGE_PASSWORD_FORM'] === 'Y') {
            ob_start();
            $APPLICATION->IncludeComponent(
                'bitrix:main.auth.changepasswd',
                '.default',
                array(
                    'AUTH_AUTH_URL' => $arResult['PATH_TO_PRIVATE'],
//					'AUTH_REGISTER_URL' => 'register.php',
                ),
                false
            );
            $authForm = ob_get_clean();
        } else {
            ob_start();
            $APPLICATION->IncludeComponent(
                'bitrix:main.auth.form',
                '.default',
                array(
                    'AUTH_FORGOT_PASSWORD_URL' => $arResult['PATH_TO_PASSWORD_RESTORE'],
//					'AUTH_REGISTER_URL' => 'register.php',
                    'AUTH_SUCCESS_URL' => $arResult['AUTH_SUCCESS_URL'],
                    'DISABLE_SOCSERV_AUTH' => $arParams['DISABLE_SOCSERV_AUTH'],
                ),
                false
            );
            $authForm = ob_get_clean();
        }
    }

    echo $authForm;
} else {
    ?>
    <div class="three-cols-lk-container">
        <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "left",
            array(
                "COMPONENT_TEMPLATE" => "left",
                "ROOT_MENU_TYPE" => "personal",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "360000",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => array(),
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "N",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO"
            ),
            false
        ); ?>
        <?php
        $APPLICATION->IncludeComponent(
            "bitrix:main.profile",
            "personal",
            array(
                "SET_TITLE" => $arParams["SET_TITLE"],
                "AJAX_MODE" => $arParams['AJAX_MODE_PRIVATE'],
                "SEND_INFO" => $arParams["SEND_INFO_PRIVATE"],
                "CHECK_RIGHTS" => $arParams['CHECK_RIGHTS_PRIVATE'],
                "EDITABLE_EXTERNAL_AUTH_ID" => $arParams['EDITABLE_EXTERNAL_AUTH_ID'],
                "DISABLE_SOCSERV_AUTH" => $arParams['DISABLE_SOCSERV_AUTH'],
                "USER_PROPERTY" => ['PERSONAL_PHONE']
            ),
            $component
        );


        ?>
    </div>
    <?php
}
