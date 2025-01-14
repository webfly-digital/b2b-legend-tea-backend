<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

CJSCore::Init(array("ajax"));

$APPLICATION->AddHeadScript('https://code.jquery.com/jquery-3.7.1.min.js');

?>
<script>
	BX.ready(function(){
		var input = BX("<?echo $arResult["ID"]?>");
		if (input) new JsSuggest(input, '<?echo $arResult["ADDITIONAL_VALUES"]?>');
	});
</script>
<input name="<?echo $arParams["NAME"]?>" id="<?echo $arResult["ID"]?>" value="<?echo $arParams["VALUE"]?>" type="text" autocomplete="off" placeholder="Поиск по каталогу" />
<iframe style="width:0px; height:0px; border: 0px;" src="javascript:''" name="<?echo $arResult["ID"]?>_div_frame" id="<?echo $arResult["ID"]?>_div_frame"></iframe>

<script>
    $(document).ready(function(){

        $(document).on('click', '.search-show-more', function (e) {

            e.preventDefault();
            let id = $(this).data('id')
            let form_url = '/bitrix/templates/b2b/components/bitrix/search.suggest.input/.default/ajax.php'
            let params = 'id='+id

                $.ajax({
                    url: form_url,
                    type: 'GET',
                    data: params,
                    success: function (data) {
                        $('.product-slide-info').addClass('show')
                        $('.product-slide-info .product-slide-content').empty().html(data)

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            return false;
        });
        $(document).on('click', '.icon-cros.close', function (e) {
            $('.product-slide-info').removeClass('show')
        });

    })
</script>