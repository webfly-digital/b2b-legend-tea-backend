<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?>
<div class="container-size-3">
    <div class="contacts">
        <h1><?$APPLICATION->ShowTitle(false);?></h1>
        <div class="info">
            <div class="block">
                <a href="tel:<?= file_get_contents(SITE_DIR . "include/contacts/phone.php") ?>" class="item">
                    <div class="icon icon-phone"></div>
                    <p><? $APPLICATION->IncludeFile(SITE_DIR . "include/contacts/phone.php", array(), array("MODE" => "text", "NAME" => "телефон")); ?></p>
                </a>

                <a href="mailto:<?= file_get_contents(SITE_DIR . "include/contacts/email.php") ?>" class="item">
                    <div class="icon icon-phone"></div>
                    <p><? $APPLICATION->IncludeFile(SITE_DIR . "include/contacts/email.php", array(), array("MODE" => "text", "NAME" => "email")); ?></p>
                </a>

            </div>
            <div class="block">
                <div class="item">
                    <div class="icon icon-location"></div>
                    <? $APPLICATION->IncludeFile(SITE_DIR . "include/contacts/address.php", array(), array("MODE" => "html", "NAME" => "адрес")); ?>
                </div>
            </div>
            <div class="block">
                <div class="item">
                    <p>Соц. сети:</p>
                    <? $APPLICATION->IncludeFile(SITE_DIR . "include/contacts/soc.php", array(), array("MODE" => "html", "NAME" => "адрес")); ?>
                </div>
            </div>
        </div>
        <div class="map">
            <div class="content">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A4dd74a2749c1d4ad74be31296fbbc634672174d03b35b6cf4963c06e5439baa8&amp;source=constructor" width="500" height="400" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
