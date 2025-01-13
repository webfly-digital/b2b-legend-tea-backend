<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");

$userName = $USER->GetFullName();
if (!$userName)
    $userName = $USER->GetLogin();
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
