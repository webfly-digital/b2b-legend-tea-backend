<template id="checkout-basket">
  <div class="cart" v-if="length > 0">
    <div class="cart-header">
      <h3>Ваш заказ</h3>
    </div>
    <div class="cart-body">
      <div class="prices">
        <div class="item" v-for="item in basket" :key="item.ID">
          <div class="name">{{item.NAME}}</div>
          <div class="price">{{item.SUM_FORMAT}}</div>
        </div>
        <div class="item">
          <div class="name">Доставка</div>
          <div class="price">{{total.DELIVERY_PRICE_FORMATED}}</div>
        </div>
        <div class="item">
          <div class="name">Скидка</div>
          <div class="price">{{total.DISCOUNT_PRICE_FORMATED}}</div>
        </div>
      </div>
      <div class="note">
        <span>Добавьте еще 6 кг для скидки</span> <span class="text-red">5%</span>
        <a href="/rules/#83809" class="text-red">Подробнее</a>
      </div>
    </div>
    <div class="cart-footer">
      <div class="total">
        <span>итого</span>
        <span>{{total.ORDER_TOTAL_PRICE_FORMATED}}</span>
      </div>
      <button type="submit" class="button-full" :disabled="total.BTN_DISABLED == 'Y'"><span>Оформить заказ</span>
        <div class="icon icon-arrow-right"></div></button>
    </div>
  </div>
</template>