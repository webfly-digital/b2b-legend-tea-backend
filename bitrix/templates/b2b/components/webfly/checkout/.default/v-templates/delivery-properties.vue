<template id="checkout-delivery-properties">
  <div v-if="length > 0">
    <template v-for="group in grouped_properties" :key="group.ID" v-if="group.ID==18 || group.ID==19">
      <div class="container-size-2 ml-0 p-0" :style="[group.SHOW == 'N' ? {'display': 'none'} : {}]">
        <div class="custom-form">
          <div class="form-group">
            <div class="form-col">
              <template v-for="propertiesRow in group.properties">
                <div :key="propertiesRow.ID" class="form-row" :style="[propertiesRow.HIDE == 'Y' ? {'display': 'none'} : {}]">
                  <div class='form-cell' v-for="property in propertiesRow.PROPS">
                    <!--Св-во типа "Список"-->
                    <label :for="'soa-property-' + property.ID"  class="custom-select">
                      <select :required="property.REQUIRED == 'Y'" :name="'ORDER_PROP_' + property.ID" v-if="property.ID"
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
