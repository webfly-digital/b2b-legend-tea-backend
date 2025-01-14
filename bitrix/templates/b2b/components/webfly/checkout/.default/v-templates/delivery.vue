<template id="checkout-delivery">
  <div class="delivery-cards mt-4">
    <label class="card" v-for="item in delivery" :for="'ID_DELIVERY_ID_' + item.ID" :key="item.ID">
      <input type="radio" name="DELIVERY_ID" :id="'ID_DELIVERY_ID_' + item.ID" :value="item.ID"
             :checked="item.CHECKED == 'Y'" @change="refresh">
      <div class="visual">
        <div class="top">
          <div class="logo" v-if="item.LOGOTIP &&  item.LOGOTIP.SRC">
            <img :src="item.LOGOTIP.SRC" :alt="item.NAME">
          </div>
          <div class="info">
            <h4 v-html="item.OWN_NAME"></h4>
            <div v-html="item.PERIOD_TEXT"></div>
          </div>
          <div class="price">{{ item.PRICE_FORMATED }}</div>
        </div>
        <!--Кнопка "Выбрать ПВЗ" СДЭК-->
        <div v-if="item.SDEK_PVZ_BTN == 'Y'" :id="'IPOLSDEK_injectHere-'+item.ID" style="margin-top:12px;">
          <img src="/include/loader-btn.svg" style="margin:0 auto;">
        </div>
        <!--Кнопка "Посмотреть на карте"-->
        <div v-if="item.SHOW_MAP == 'Y'" class="bottom">
          <div class="check-map" @click="openYaCart" :data-target="item.MAP_TARGET">Посмотреть на карте</div>
        </div>
        <div v-if="item.SHOW_HTML == 'Y'" class="bottom" v-html="item.DESCRIPTION">

        </div>
      </div>
    </label>
  </div>
</template>
