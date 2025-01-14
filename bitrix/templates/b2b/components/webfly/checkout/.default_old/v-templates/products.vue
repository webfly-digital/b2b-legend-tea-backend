<template id="checkout-products">
  <div class="product-table-group">
    <div class="product-table-wrapper">
      <div class="product-table type6">
        <div class="product-table-header">
          <div class="cell">Наименование</div>
          <div class="cell">Доп. информация</div>
          <div class="cell">Цена</div>
          <div class="cell">Количество</div>
          <div class="cell">Сумма</div>
        </div>
        <div class="product-table-body">
          <template v-for="oneSection in products">
            <template v-for="product in oneSection.ROWS">
              <div :key="product.data.ID" class="product-table-row" :data-id="product.data.ID">
                <div class="product-table-cell">
                  <div class="product-header">
                    <div class="left">
                    </div>
                    <div class="imgholder">
                      <img :src="product.data.DETAIL_PICTURE_SRC" alt="">
                    </div>
                    <div class="right">
                      <span class="detail-opener-btn" @click="showDetailInfo">{{ product.data.NAME }}</span>
                      <div class="labels">
                        <div class="label grey-noborder" v-html="product.data.CML2_ARTICLE"></div>
                        <div class="label dark" v-if="product.data.DETAIL_DATA.TIME_DELIVERY"  @click="showPopupStock">
                          <div class="icon icon-packageLabel"></div>
                          <span>Под заказ</span>
                          <div class="out-of-stock-content">Срок поставки до <span class="day">{{product.data.DETAIL_DATA.TIME_DELIVERY}}</span> дней</div>
                        </div>
                      </div>
                      <p v-if="product.data.TEXT_SKU">{{ product.data.TEXT_SKU}}</p>
                    </div>
                  </div>
                </div>
                <div class="product-table-cell">
                  <template v-if="product.data.COLUMNS || product.data.LIST_SKU">
                    <template v-if="product.data.LIST_SKU">
                      <div class="select">
                        <select  class="choices"  @change="changeSKU">
                          <template v-for="sku in product.data.LIST_SKU">
                            <option :value="sku.ID_SKU" :selected="sku.SELECTED == 'Y'" :data-custom-properties="sku.EXIST">
                              {{sku.UPAKOVKA_VALUE}}
                            </option>
                          </template>
                        </select>
                      </div>
                    </template>
                    <template v-if="product.data.COLUMNS">
                      <template v-for="column in product.data.COLUMNS">
                        <dt v-if="product.data.PROPERTIES[column]">{{ product.data.PROPERTIES[column]}}</dt>
                      </template>
                    </template>
                  </template>
                  <span v-else>-</span>
                </div>
                <div class="product-table-cell" v-html="product.data.PRICE_FORMATED"></div>
                <div class="product-table-cell">
                  <div class="quantity">
                    <div class="icon icon-minus" data-value="-1" @click="quantityMinus"></div>
                    <input type="number" name="QUANTITY" :value="product.data.QUANTITY" @change="quantityChange">
                    <div class="icon icon-plus" data-value="1" @click="quantityPlus"></div>
                  </div>
                </div>
                <div class="product-table-cell" v-html="product.data.SUM_FORMATED"></div>
                <div class="product-table-cell">
                  <div class="icon icon-cross" @click="remove"></div>
                </div>
                <div class="detail-info" style="display:none;">
                  <div class="header">
                    <div class="icon icon-cross close"></div>
                  </div>
                  <div class="body">
                    <div class="slider">
                      <div class="splide" v-if="product.data.DETAIL_DATA.PICTURES">
                        <div class="splide__track">
                          <ul class="splide__list">
                            <li class="splide__slide" v-for="pic in product.data.DETAIL_DATA.PICTURES" :key="pic.src">
                              <div class="slide">
                                <div class="content">
                                  <img :src="pic.src"
                                       :alt="product.data.DETAIL_DATA.FIELDS.NAME"
                                       :title="product.data.DETAIL_DATA.FIELDS.NAME">
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="subslider">
                      <div class="labels">
                        <div class="label grey-noborder" v-if="product.data.DETAIL_DATA.PROPERTIES.PROPERTY_CML2_ARTICLE">
                          {{ product.data.DETAIL_DATA.PROPERTIES.PROPERTY_CML2_ARTICLE }}
                        </div>
                        <div :class="product.data.DETAIL_DATA.LABEL.CLASS" v-if="product.data.DETAIL_DATA.LABEL">
                          <div :class="product.data.DETAIL_DATA.LABEL.ICON"></div>
                          <span>{{ product.data.DETAIL_DATA.LABEL.TEXT }}</span>
                        </div>
                      </div>
                    </div>
                    <h3>{{ product.data.DETAIL_DATA.FIELDS.NAME }}</h3>
                    <div class="subtitle">{{ product.data.DETAIL_DATA.FIELDS.PREVIEW_TEXT }}</div>
                    <div class="characteristics">
                      <h4 class="title" v-if="product.data.DETAIL_DATA.DISPLAY_PROPERTIES">Характеристики:</h4>
                      <div class="text-items" v-if="product.data.DETAIL_DATA.DISPLAY_PROPERTIES">
                        <div class="item" v-for="displayProperty in product.data.DETAIL_DATA.DISPLAY_PROPERTIES"
                             :key="displayProperty.ID">
                          <div class="name">{{ displayProperty.NAME }}:</div>
                          <div class="value">{{ displayProperty.DISPLAY_VALUE }}</div>
                        </div>
                      </div>
                      <div class="progress-items" v-if="product.data.DETAIL_DATA.RANGES">
                        <div class="item" v-for="rangeItem in product.data.DETAIL_DATA.RANGES" :key="rangeItem.NAME">
                          <div class="progress"
                               :style="rangeItem.VALUE"></div>
                          <div class="text">{{ rangeItem.NAME }}</div>
                        </div>
                      </div>
                      <div class="description" v-if="product.data.DETAIL_DATA.DESCRIPTION">
                        <div class=" text-content">
                          <h4 class="title">Описание:</h4>
                          <div v-html="product.data.DETAIL_DATA.DESCRIPTION"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </template>
        </div>
      </div>
    </div>
    <div class="product-table-group-footer">
      <span>Итоговая сумма: </span>
      <div class="price" v-html="total.PRODUCTS_PRICE_FORMATED"></div>
    </div>
  </div>
</template>
