<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */

$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;
$this->addExternalJs($templateFolder . '/updater.js');
?><script>
BasketToCatalogUpdater = new BasketToCatalogUpdater();
var <?=$cartId?> = new BitrixSmallCart;
</script>
<div id="<?=$cartId?>" class=""><?
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
