<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");

$userName = $USER->GetFullName();
if (!$userName)
    $userName = $USER->GetLogin();


if (array_key_exists('REQUEST_1C', $_REQUEST) && !empty($_REQUEST["REQUEST_1C"])) { //возввращаем json ответ, если запрос из 1С
    $arResult['TYPE'] = 'SUCCESS';
    $arResult['MESSAGE']['USER_ID'] = $USER->getId();
    $arResult['MESSAGE']['PROFILE_ID'] = \Webfly\Helper\Functions::getFirstProfileBuyer($USER->getId())['ID'];
    echo(json_encode($arResult));
    die;
}

?>
<div class="reg-done">
    <div class="container-size-2">
        <img src="<?= SITE_TEMPLATE_PATH ?>/assets/static/img/logo-notext.png" alt="">
        <h1>Вы успешно авторизовались!</h1>
        <a href="<?= SITE_DIR ?>catalog/" class="button-fixed">В каталог</a>
    </div>
</div>

<script>
    <?if ($userName):?>
    BX.localStorage.set("eshop_user_name", "<?=CUtil::JSEscape($userName)?>", 604800);
    <?else:?>
    BX.localStorage.remove("eshop_user_name");
    <?endif?>
    <?if (isset($_REQUEST["backurl"]) && $_REQUEST["backurl"] <> '' && preg_match('#^/\w#', $_REQUEST["backurl"])):?>
    document.location.href = "<?=CUtil::JSEscape($_REQUEST["backurl"])?>";
    <?endif?>

</script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
