<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
<div class="reg-done">
    <div class="container-size-2">
        <img src="<?= SITE_TEMPLATE_PATH ?>/assets/static/img/logo-notext.png" alt="">
        <h1>Благодарим за регистрацию!</h1>
        <div class="subtitle"> Менеджер свяжется с вами по телефону для подтверждения регистрации, настроит личный
            кабинет под вашу компанию и пригласит воспользоваться сервисом по указанной электронной почте
        </div>
        <a href="/" class="button-fixed">На главную</a>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
