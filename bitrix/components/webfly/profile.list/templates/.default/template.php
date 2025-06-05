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

use Bitrix\Main\Context;

$request = Context::getCurrent()->getRequest();
$this->setFrameMode(true);


?>
<div class="block">
    <div class="drop-block">
        <div class="icon-link">
            <span class="suptitle">Юридическое лицо</span>
            <div class="icon icon-profile-login"></div>
            <span>Юридическое лицо</span>
            <div class="icon icon-arrow-down"></div>
        </div>
        <div class="drop">
            <?foreach ( $arResult['ITEMS']  as $item):
                echo "<pre>";
                var_dump($item);
                echo "</pre>";
                ?>
            <a href="">ИП Бакадорова Татьяна Юрьевна</a>
            <?endforeach?>
            <a href="" class="text-red thin">Добавить новое юр. лицо</a>
        </div>
    </div>
</div>