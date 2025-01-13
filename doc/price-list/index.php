<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>
<div class="container-size-3">
	<p class="h2">Скачать прайс листы</p>
	<nav class="price-list-links">
<!--		<a href="/upload/price-list-files" class=""><div class="icon icon-docMini"></div>Прайс-лист</a>-->
		<a href="/upload/price-list-files/chay-price-list.pdf" class="" download><div class="icon icon-new-tea"></div>Прайс-лист "Чай"</a>
		<a href="/upload/price-list-files/goryachie_napitki-price-list.pdf" class="" download><div class="icon icon-new-hot-drinks"></div>Прайс-лист "Горячие напитки"</a>
		<a href="/upload/price-list-files/dobavki_v_chay-price-list.pdf" class="" download><div class="icon icon-new-ingredients"></div>Прайс-лист "Добавки к чаю"</a>
		<a href="/upload/price-list-files/zelenyy_kofe-price-list.pdf" class="" download><div class="icon icon-new-green-coffee"></div>Прайс-лист "Зеленый кофе"</a>
		<a href="/upload/price-list-files/kofe-price-list.pdf" class="" download><div class="icon icon-new-coffee-montis"></div>Прайс-лист "Кофе"</a>
		<a href="/upload/price-list-files/posuda_i_aksessuary-price-list.pdf" class="" download><div class="icon icon-new-tableware"></div>Прайс-лист "Посуда и аксессуары"</a>
		<a href="/upload/price-list-files/sladosti-price-list.pdf" class="" download><div class="icon icon-new-candies-big"></div>Прайс-лист "Сладости к чаю и кофе"</a>
		<a href="/upload/price-list-files/upakovka-price-list.pdf" class="" download><div class="icon icon-new-package"></div>Прайс-лист "Упаковка"</a>
	</nav>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
       if(document.documentElement.clientWidth <= 1200){
           let links = document.querySelectorAll('.price-list-links a');
           if(links.length > 0){
               links.forEach((x, index) => {
                  if(x.hasAttributes('download')){
                      x.removeAttribute('download')
                  }
               })
           }
       }
    });
</script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
