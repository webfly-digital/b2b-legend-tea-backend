<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Сменить пароль");

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
            "webfly:change.password",
            "personal",
            Array(
            ),
           false
        );

        ?>
    </div>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>