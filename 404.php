<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404", "Y");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("404 Not Found");

?>
    <section>
        <div class="three-cols-lk-container">
            <div class="left"></div>
            <div class="mid">
                <div class="text-content">
                    <h4>Страница не найдена!</h4>
                    <p>Перейти в <a href="/catalog/">каталог</a></p>
                </div>
            </div>
            <div class="right"></div>
        </div>
    </section>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>