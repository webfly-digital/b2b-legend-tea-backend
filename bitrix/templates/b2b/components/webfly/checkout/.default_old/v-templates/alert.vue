<template id="checkout-alert">
  <div>
    <div class="form-row" v-if="total.DELIVERY_TIME">
      <div class="form-cell">
        <div class="note red">
          <span class="text-red">Есть товар со статусом «под заказ», отгрузка будет {{ total.DELIVERY_TIME }}</span>
        </div>
      </div>
    </div>
    <div class="form-row" v-if="total.EXIST_COMPANY">
      <div class="form-cell">
        <div class="note red">
          <span class="text-red" v-html="total.EXIST_COMPANY"></span>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-cell">
        <button :disabled="total.BTN_DISABLED == 'Y'" type="submit" class="button-full">Оформить</button>
        <p class="text mt-2"> Оформляя заказ я принимаю изложенное в следующих
          документах: <a href="/rules/" class="link">Условиями продажи</a>, <a
              href="/rules/#83810" class="link">Условия
            обработки платежей</a>, <a href="/userconsent/" class="link">Политика
            конфиденциальности</a>
        </p>
      </div>
    </div>
  </div>
</template>