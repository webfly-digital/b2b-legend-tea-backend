<template id="checkout-region-block">
  <section>
    <div class="container-size-2 ml-0 p-0">
      <div class="custom-form">
        <div class="form-group">
          <div class="form-col">
            <!--Выбор Физическое/Юридическое лицо-->
            <div class="form-row" v-if="length > 0" :style="[length==1 ? {'display': 'none'} : {}]">
              <div class="form-cell" v-for="pType in regiondata.persontype" :key="pType.ID">
                <label class="custom-radio-label type-profile" :for="'person-type-' + pType.ID" :key="pType.ID">
                  <input type="radio" name="PERSON_TYPE" class="custom-radio" :value="pType.ID"
                         :id="'person-type-' + pType.ID" :checked="pType.CHECKED == 'Y'" @change="refresh"/>
                  <span>{{ pType.NAME }}</span>
                </label>
              </div>
              <div class="form-cell"></div>
              <input v-if="oldPersonType" name="PERSON_TYPE_OLD" :value="oldPersonType" hidden>
            </div>
            <!--Выбор профиля-->
            <div class="form-row" v-if="showProfileSelect">
              <div class="form-cell">
                <div class="custom-select">
                  <input type="hidden" value="N" id="profile_change" name="profile_change"/>
                  <select name="PROFILE_ID" id="PROFILE_ID" @change="updateProfile">
                    <option value="0">Новый профиль</option>
                    <option v-for="pItem in regiondata.profile" :key="pItem.ID" :value="pItem.ID" v-html="pItem.NAME"
                            :selected="pItem.CHECKED == 'Y'"></option>
                  </select>
                  <div class="input-title" v-if="checkedPersonType == 6"> Выберите профиль </div>
                  <div class="input-title" v-else> Выберите компанию </div>
                </div>
              </div>
              <div class="form-cell"></div>
            </div>
            <div class="form-row" v-else>
              <input type="hidden" name="PROFILE_ID" :value="pItem.ID" v-for="pItem in regiondata.profile" :key="pItem.ID" />
            </div>
            <!--Местоположение-->
            <div class="form-row">
             <div class="location-row form-cell"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>


