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
              <div :key="product.data.ID" class="product-table-row" :data-id="product.data.ID"
                   :data-product-id="product.data.PRODUCT_ID"
                   :data-parent-id="product.data.PARENT_ID?product.data.PARENT_ID:product.data.PRODUCT_ID">
                <div class="product-table-cell">
                  <div class="product-header">
                    <div class="left">
                    </div>
                    <div class="imgholder" @click="showDetailInfo">
                      <img :src="product.data.DETAIL_PICTURE_SRC" alt="">
                    </div>
                    <div class="right">
                      <span @click="showDetailInfo">{{ product.data.NAME }}</span>
                      <div class="labels">
                        <div class="label grey-noborder" v-html="product.data.CML2_ARTICLE"></div>
                        <div class="label dark" v-if="product.data.DETAIL_DATA.TIME_DELIVERY" @click="showPopupStock">
                          <div class="icon icon-packageLabel"></div>
                          <span>Под заказ</span>
                          <div class="out-of-stock-content">Срок поставки до <span
                              class="day">{{ product.data.DETAIL_DATA.TIME_DELIVERY }}</span> дней
                          </div>
                        </div>
                      </div>
                      <p v-if="product.data.TEXT_SKU">{{ product.data.TEXT_SKU }}</p>
                    </div>
                  </div>
                </div>
                <div class="product-table-cell">
                  <template
                      v-if="product.data.COLUMNS ||  product.data.PROPERTIES &&  product.data.PROPERTIES.UPAKOVKA">
                    <template v-if=" product.data.PROPERTIES.UPAKOVKA">
                      <div class="pseudo-select" @click="showDetailInfo">
                        <span>{{ product.data.PROPERTIES.UPAKOVKA }}</span>
                      </div>
                    </template>
                    <template v-if="product.data.COLUMNS">
                      <template v-for="column in product.data.COLUMNS">
                        <dt v-if="product.data.PROPERTIES[column]">{{ product.data.PROPERTIES[column] }}</dt>
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
              </div>
            </template>
          </template>
        </div>
      </div>
      <div>
        <template v-for="card in cards">
          <div class="detail-info" :data-parent-id-card="card.PARENT_ID?card.PARENT_ID:card.PRODUCT_ID" :key="card.ID">
            <div class="side-buttons">
              <div class="side-button close">
                <div class="icon icon-cross "></div>
                <p>Закрыть</p>
              </div>
            </div>
            <div class="body">
              <div class="product-slide-header">
                <div class="slider">
                  <div class="splide" v-if="card.DETAIL_DATA.PICTURES">
                    <div class="splide__track">
                      <ul class="splide__list">
                        <li class="splide__slide" v-for="(pic, index) in card.DETAIL_DATA.PICTURES"
                            :key="pic.src">
                          <a class="slide glightbox" :href="card.DETAIL_DATA.PICTURES_ORIGINAL[index]"
                             :data-gallery="card.DETAIL_DATA.FIELDS.ID">
                            <div class="content">
                              <img :src="pic.src"
                                   :alt="card.DETAIL_DATA.FIELDS.NAME"
                                   :title="card.DETAIL_DATA.FIELDS.NAME">
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="splide" v-else>
                    <div class="splide__track">
                      <ul class="splide__list">
                        <li class="splide__slide">
                          <a class="slide glightbox" :href="card.DETAIL_PICTURE_SRC"
                             :data-gallery="card.DETAIL_DATA.FIELDS.ID">
                            <div class="content">
                              <img :src="card.DETAIL_PICTURE_SRC"
                                   :alt="card.DETAIL_DATA.FIELDS.NAME"
                                   :title="card.DETAIL_DATA.FIELDS.NAME">
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="subslider">
                  <div class="labels">
                    <div class="label grey-noborder" v-if="card.DETAIL_DATA.PROPERTIES.PROPERTY_CML2_ARTICLE">
                      {{ card.DETAIL_DATA.PROPERTIES.PROPERTY_CML2_ARTICLE }}
                    </div>
                    <div :class="card.DETAIL_DATA.LABEL.CLASS" v-if="card.DETAIL_DATA.LABEL">
                      <div :class="card.DETAIL_DATA.LABEL.ICON"></div>
                      <span>{{ card.DETAIL_DATA.LABEL.TEXT }}</span>
                    </div>
                  </div>
                  <h3>{{ card.DETAIL_DATA.FIELDS.NAME }}</h3>
                  <div class="subtitle">{{ card.DETAIL_DATA.FIELDS.PREVIEW_TEXT }}</div>
                  <div class="characteristics">
                    <h4 class="title" v-if="card.DETAIL_DATA.DISPLAY_PROPERTIES">Характеристики:</h4>
                    <div class="text-items" v-if="card.DETAIL_DATA.DISPLAY_PROPERTIES">
                      <div class="item" v-for="displayProperty in card.DETAIL_DATA.DISPLAY_PROPERTIES"
                           :key="displayProperty.ID">
                        <div class="name">{{ displayProperty.NAME }}:</div>
                        <div class="value">{{ displayProperty.DISPLAY_VALUE }}</div>
                      </div>
                    </div>
                    <div class="progress-items" v-if="card.DETAIL_DATA.RANGES">
                      <div class="item" v-for="rangeItem in card.DETAIL_DATA.RANGES"
                           :key="rangeItem.NAME">
                        <div class="progress"
                             :style="rangeItem.VALUE"></div>
                        <div class="text">{{ rangeItem.NAME }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="product-slide-sizes">
                <template v-if="card.LIST_SKU">
                  <template v-for="sku in card.LIST_SKU">
                    <div :class="sku.CATALOG_QUANTITY > 0 ?'size in-stock':'size out-of-stock'" :key="sku.ID">
                      <div class="product-table-cell">
                        <span>{{ sku.PROPERTY_UPAKOVKA_VALUE }}</span>
                      </div>
                      <div class="product-table-cell">
                        <div class="select" v-if="sku.POMOL" :data-selected="sku.SELECTED" :data-json="sku.JSON?sku.JSON:''" >
                          <select v-if="sku.SELECTED" @change="changeSKU" :value="sku.SELECTED" class="choices" :data-selected="sku.SELECTED" >
                            <template v-for="(pomol, name, index) in sku.POMOL">
                              <option  v-if="pomol.SELECTED=='Y'" :value="pomol.SKU_ID" selected :key="index">
                                {{ pomol.PROPERTY_POMOL_VALUE }}
                              </option>
                              <option v-else :value="pomol.SKU_ID" :key="index">
                                {{ pomol.PROPERTY_POMOL_VALUE }}
                              </option>
                            </template>
                          </select>
                          <select v-else @change="changeSKU" class="choices">
                            <template v-for="(pomol, name, index) in sku.POMOL">
                              <option :value="pomol.SKU_ID" :selected="index == '0'">
                                {{ pomol.PROPERTY_POMOL_VALUE }}
                              </option>
                            </template>
                          </select>
                        </div>
                      </div>
                      <div class="product-table-cell"><span data-price="">{{ sku.PRICE }}</span></div>
                      <div class="product-table-cell">
                        <div class="quantity">
                          <div class="icon icon-minus" :data-card="sku.SKU_ID?sku.SKU_ID:''" data-value="-1"
                               @click="quantityMinus"></div>
                          <input type="number" name="QUANTITY" :data-card="sku.SKU_ID?sku.SKU_ID:''"
                                 :value="sku.QUANTITY?sku.QUANTITY:0"
                                 @change="quantityChange">
                          <div class="icon icon-plus" data-value="1" :data-card="sku.SKU_ID?sku.SKU_ID:''"
                               @click="quantityPlus"></div>
                        </div>
                      </div>
                    </div>
                  </template>
                </template>
                <template v-else>
                  <div :class="card.DETAIL_DATA.TIME_DELIVERY ?'size out-of-stock':'size in-stock'">
                    <div class="product-table-cell"></div>
                    <div class="product-table-cell"></div>
                    <div class="product-table-cell"><span>{{ card.PRICE }}</span></div>
                    <div class="product-table-cell">
                      <div class="quantity">
                        <div class="icon icon-minus" :data-card="card.PRODUCT_ID" data-value="-1"
                             @click="quantityMinus"></div>
                        <input type="number" name="QUANTITY" data-card='' :value="card.QUANTITY?card.QUANTITY:0"
                               @change="quantityChange">
                        <div class="icon icon-plus" data-value="1" :data-card="card.PRODUCT_ID"
                             @click="quantityPlus"></div>
                      </div>
                    </div>
                  </div>
                </template>
              </div>

              <div class="product-slide-description"
                   v-if="card.DETAIL_DATA.DESCRIPTION">
                <nav>
                  <label v-if="card.DETAIL_DATA.DESCRIPTION">
                    <input type="radio" name="lol">
                    <p>Описание</p>
                  </label>
<!--                  <label v-if="card.DETAIL_DATA.GENERATE_PDF">-->
<!--                    <input type="radio" name="lol">-->
<!--                    <p>Файлы для скачивания</p>-->
<!--                  </label>-->
                </nav>
                <div class="content">
                  <div class="content_block" v-if="card.DETAIL_DATA.DESCRIPTION" v-html="card.DETAIL_DATA.DESCRIPTION ">
                  </div>
                  <div class="content_block" v-if="card.DETAIL_DATA.GENERATE_PDF">
                    <nav class="links">
                      <div class="download" data-generate-pdf-btn="">
                        Маркетинговые файлы
                      </div>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
    <div class="product-table-group-footer">
      <span>Итоговая сумма: </span>
      <div class="price" v-html="total.PRODUCTS_PRICE_FORMATED"></div>
    </div>
  </div>
</template>
