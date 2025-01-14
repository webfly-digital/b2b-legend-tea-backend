<template id="checkout-delivery-prop">
  <div v-if="length > 0">
    <template v-for="group in grouped_properties" :key="group.ID" v-if="group.ID==11 || group.ID==15">
      <h3 class="group-title" :style="[group.SHOW == 'N' ? {'display': 'none'} : {}]">{{ group.NAME }}</h3>
      <div class="container-size-2 ml-0 p-0" :style="[group.SHOW == 'N' ? {'display': 'none'} : {}]">
        <div class="custom-form">
          <div class="form-group">
            <div class="form-col">
              <template v-for="propertiesRow in group.properties">
                <div :key="propertiesRow.ID" class="form-row" :style="[propertiesRow.HIDE == 'Y' ? {'display': 'none'} : {}]">
                  <div :class="[property.ID==44  ? 'form-cell big' : 'form-cell']" v-for="property in propertiesRow.PROPS"
                       :style="[property.HIDE == 'Y' ? {'display': 'none'} : {}]">
                    <!--Св-во типа "Строка"-->
                    <label v-if="property.TYPE == 'STRING'" class="custom-input" :key="property.ID"
                           :for="'soa-property-' + property.ID" v-if="property.TYPE == 'STRING'">
                      <input :placeholder="property.DESCRIPTION" :readonly="property.READONLY == 'Y'"
                             :class="property.CLASS" :required="property.REQUIRED == 'Y'" type="text"
                             :id="[property.HTML_ID ? property.HTML_ID :  'soa-property-' + property.ID]" :name="'ORDER_PROP_' + property.ID"
                             :value="property.VALUE" :data-minlength="property.MINLENGTH"
                             :data-maxlength="property.MAXLENGTH" />
                      <div class="input-title">{{ property.NAME }}</div>
                    </label>
                    <!--Св-во типа "Да/Нет"-->
                    <label class="checkbox-group" :for="'soa-property-' + property.ID" v-else-if="property.TYPE == 'Y/N'">
                      <input type="hidden" :name="'ORDER_PROP_' + property.ID" value="N">
                      <input :required="property.REQUIRED == 'Y'" :name="'ORDER_PROP_' + property.ID" value="Y"
                             type="checkbox" class="custom-checkbox" :id="'soa-property-' + property.ID"
                             :checked="property.CHECKED == 'Y'"/>
                      <span>{{ property.NAME }}</span>
                    </label>
                    <!--Св-во типа "Список"-->
                    <label :for="'soa-property-' + property.ID" v-else-if="property.TYPE == 'ENUM'" class="custom-select">
                      <select :required="property.REQUIRED == 'Y'" :name="'ORDER_PROP_' + property.ID"
                              :id="'soa-property-' + property.ID">
                        <option value="">Выберите</option>
                        <template v-for="option in property.V_OPTIONS">
                          <option :value="option.VALUE" :selected="option.SELECTED == 'Y'">{{ option.NAME }}</option>
                        </template>
                      </select>
                      <div class="input-title">{{ property.NAME }}</div>
                    </label>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
