<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */

$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;
?><script>
    var <?=$cartId?> = new BitrixSmallCartTop;
</script>
<div id="<?=$cartId?>" class="ml-2 <?=$APPLICATION->getCurDir() == SITE_DIR || CSite::InDir('/catalog/')?'d-mobile':''?>"><?
    /** @var \Bitrix\Main\Page\FrameBuffered $frame */
    require(realpath(dirname(__FILE__)).'/ajax_template.php');
    ?></div>
<script type="text/javascript">
    <?=$cartId?>.siteId       = '<?=SITE_ID?>';
    <?=$cartId?>.cartId       = '<?=$cartId?>';
    <?=$cartId?>.ajaxPath     = '<?=$componentPath?>/ajax.php';
    <?=$cartId?>.templateName = '<?=$templateName?>';
    <?=$cartId?>.arParams     =  <?=CUtil::PhpToJSObject ($arParams)?>;
    <?=$cartId?>.activate();
</script>
