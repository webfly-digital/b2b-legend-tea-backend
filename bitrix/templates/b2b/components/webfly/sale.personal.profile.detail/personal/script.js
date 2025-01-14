(function () {
    'use strict';

    if (!!window.JSWebflySaleProfile)
        return;

    window.JSWebflySaleProfile = function () {
        this.lastCall = '';
        this.lastCallTimer = '';
        this.loader = BX('fullscreenLoader');
        this.form = BX('sale-profile_form');
        //    this.userIsContact = BX('USER_IS_CONTACT');
        this.personTypeSwitch = BX('PERSON_TYPE_SWITCH');
        this.idProfile = BX('ID_PROFILE');
        this.innField = this.form.querySelector('[data-code="INN"]');
        this.ajaxUrl = '/bitrix/templates/b2b/components/webfly/sale.personal.profile.detail/personal/ajax.php';
        this.existUser = BX('EXIST_USER_NOTE');
        this.saveBtn = BX('SAVE_BTN');
        this.emailUsers = BX('email_users');
        this.bxForm = BX('BX_FORM');

        this.innNote = BX('INN_NOTE');
        this.innNoteYellow = BX('INN_NOTE_YELLOW');
        this.COMPANY_ADR_Field = BX("COMPANY_ADR");
        this.COMPANY_TITLE_Field = BX("COMPANY_TITLE");
        this.company = BX('inn_note-company');
        this.companyAdr = BX('inn_note-company_adr');

        this.phoneField = this.form.querySelector('[data-code="COMPANY_PHONE"]');
        this.emailField = this.form.querySelector('[data-code="COMPANY_EMAIL"]');

        let switchFieldsNames = ['COMPANY_UF_COMPANY_NAME', 'COMPANY_EMAIL', 'COMPANY_UF_COMPANY_LAST_NAME', 'COMPANY_PHONE', 'COMPANY_UF_COMPANY_SECOND_NAME'];
        this.switchFields = [];


        this.suggestVal = () => this.suggest();
        this.processVal = (target) => this.existEmail(target);


        let field;
        for (let fieldName of switchFieldsNames) {
            field = this.form.querySelector("input[data-code='" + fieldName + "']");
            if (field)
                this.switchFields.push(field);
        }

        this.location = BX('location-row');
        if (this.location) {
            this.locationInput = this.location.querySelector('input.bx-ui-sls-fake');
            if (this.locationInput) {
                this.locationInput.setAttribute('required', true);
            }
        }

        this.bindEvents();
        initForm(this.form);
    };

    window.JSWebflySaleProfile.prototype = {
        bindEvents: function () {
            BX.bind(this.personTypeSwitch, 'change', BX.proxy(this.personTypeSwitchHandler, this));
            if (this.idProfile.value == 'NEW') {
                BX.bind(this.innField, 'input', BX.proxy(function () {
                    this.debounce(this.processVal(BX.proxy_context), 1500);
                }, this));
                BX.bind(this.phoneField, 'input', BX.proxy(function () {
                    this.debounce(this.processVal(BX.proxy_context), 1500);
                }, this));
                BX.bind(this.emailField, 'input', BX.proxy(function () {
                    this.debounce(this.processVal(BX.proxy_context), 1500);
                }, this));
            }
        },
        debounce: function (f, t) {
            let previousCall = this.lastCall;
            this.lastCall = Date.now();
            if (previousCall != '' && ((this.lastCall - previousCall) <= t)) {
                clearTimeout(this.lastCallTimer);
            }
            this.lastCallTimer = setTimeout(() => f, t);
        },
        suggest: function () {
            this.showLoader();
            if (this.innField) {
                let value = this.innField.value;
                BX.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: '/bitrix/templates/b2b/components/bitrix/system.auth.registration/flat/ajax.php',
                    data: {inn: value, sessid: BX.bitrix_sessid()},
                    onsuccess: BX.delegate(function (response) {
                        this.hideLoader();
                        if (response.result == 'success') {
                            this.hideFields(response);
                        } else {
                            this.showFields();
                        }
                    }, this),
                    onfailure: BX.delegate(function () {
                        this.hideLoader();
                        this.showFields();
                    }, this)
                });
            }
        },
        hideFields: function (response) {
            if (response.data.COMPANY) {
                BX.show(this.innNote);
                BX.hide(this.innNoteYellow);
                BX.hide(this.COMPANY_TITLE_Field);
                this.COMPANY_TITLE_Field.querySelector('input').value = this.company.innerText = response.data.COMPANY;
            }
            if (response.data.COMPANY_ADR) {
                BX.hide(this.COMPANY_ADR_Field);
                this.COMPANY_ADR_Field.querySelector('input').value = this.companyAdr.innerText = response.data.COMPANY_ADR;
            }

            if (response.data.EXIST_INN) {
                this.emailUsers.innerHTML = response.data.EXIST_INN;
            }

        },
        showFields: function () {
            BX.hide(this.innNote);
            BX.show(this.innNoteYellow);

            this.COMPANY_TITLE_Field.querySelector('input').value = this.company.innerText = '';
            this.COMPANY_ADR_Field.querySelector('input').value = this.companyAdr.innerText = '';
            BX.hide(this.innNote);
            BX.show(this.COMPANY_TITLE_Field);
            BX.show(this.COMPANY_ADR_Field);

            this.emailUsers.innerText = '';
        },
        existEmail: function (target) {
            this.showLoader();
            let value = target.value;
            let type = false;
            let type2 = false;
            let value2 = false;

            type = target.getAttribute('data-code');

            if (this.innField) type = 'inn';

            if (type == 'COMPANY_PHONE' && this.emailField && this.emailField.value) {
                type2 = 'COMPANY_EMAIL';
                value2 = this.emailField.value
            }
            if (type == 'COMPANY_EMAIL' && this.phoneField && this.phoneField.value) {
                type2 = 'COMPANY_PHONE';
                value2 = this.phoneField.value
            }

            BX.ajax({
                method: 'POST',
                dataType: 'json',
                url: this.ajaxUrl,
                data: {value: value, value2: value2, type: type, type2: type2, sessid: BX.bitrix_sessid()},
                onsuccess: BX.delegate(function (response) {
                    this.hideLoader();

                    if (response.data.EXIST_DATA) {//если есть компания с таким ИНН
                        this.emailUsers.innerHTML = response.data.EXIST_DATA;
                        BX.show(this.existUser);

                        if (type == 'inn') {
                            BX.hide(this.innNote);
                            BX.hide(this.innNoteYellow);
                            let bxForm = BX('BX_FORM');
                            let callForm = BX('CALL_FORM');
                            if (callForm) {
                                BX.bind(callForm, 'click', BX.proxy(function () {
                                    bxForm.click();
                                }, this));
                            }
                        }

                        this.saveBtn.setAttribute('disabled', 'disabled')
                    } else {
                        this.emailUsers.innerText = '';
                        BX.hide(this.existUser);
                        if (this.saveBtn.hasAttribute('disabled')) {
                            this.saveBtn.removeAttribute('disabled')
                        }
                        if (type == 'inn') this.suggest();
                    }


                }, this),
                onfailure: BX.delegate(function () {
                    this.hideLoader();
                }, this)
            });
        },
        personTypeSwitchHandler: function () {
            location.href = location.pathname + '?profile-type=' + this.personTypeSwitch.value;
        },

        showLoader: function () {
            if (this.loader)
                this.loader.classList.add("show");
        },
        hideLoader: function () {
            if (this.loader)
                this.loader.classList.remove("show");
        },
    };

})();
/**
 * Валидация
 */
let errorMes = {
    required: 'Поле не должно быть пустым',
    email: 'Некорректный email',
};

const regEmail = /^\s*[\w.-]+@[\w-]+\.[A-Za-z]{2,8}\s*$/;

const checkValue = (target) => {
    target.classList.add('validate');
    if (!target.value && target.required) {
        target.setCustomValidity(errorMes.required);
        target.closest('label').querySelector('span.err-msg').dataset.message = errorMes.required
        return;
    }
    if (target.type === 'email') {
        target.value = target.value.trim();
        if (target.value && !regEmail.test(target.value)) {
            target.setCustomValidity(errorMes.email);
            target.closest('label').querySelector('span.err-msg').dataset.message = errorMes.email
        }
        return;
    }
};

const onFocusForm = ({target}) => {
    if (target.required) {
        target.setCustomValidity('');
    }
};

const onBlurForm = ({target}) => {
    if (target.required) {
        checkValue(target);
    }
};

const preSubmit = (e) => {
    if (e.target.type === 'submit') {
        const inputs = e.currentTarget.querySelectorAll('[required]');
        inputs.forEach((input) => {
            input.classList.add('validate');
            checkValue(input);
        });
    }
};

const onSubmit = (e) => {
    e.preventDefault();
    sendData(e.target);
};
/**
 * Инит прослушивателей событий формы (хук mounted)
 * @param form
 */
const initForm = function (form) {
    form.addEventListener('click', preSubmit);
    form.addEventListener('focusin', onFocusForm);
    form.addEventListener('submit', onSubmit);
    form.addEventListener('focusout', onBlurForm);
};

const sendData = (form) => {

    let delLogo = form.querySelector('[name="FILE_REMOVE"]');
    let delLogoFile = form.querySelector('[name="ORDER_PROP_145_del"]');
    if (delLogo && delLogoFile) {
        if (delLogo.value == 'Y') {
            if (delLogoFile.hasAttribute('data-id-file')) {
                delLogoFile.value = delLogoFile.getAttribute('data-id-file')
            }
        } else {
            delLogoFile.value = '';
        }
    }

    let personType = BX('PERSON_TYPE_SWITCH');
    let nameProfile = form.querySelector('[name="NAME"]');

    if (personType && nameProfile) {
        if (personType.value == 5) {
            let companyTitle = form.querySelector('[data-code="COMPANY_TITLE"]');
            if (companyTitle) {
                nameProfile.value = companyTitle.value;
            }
        }
        if (personType.value == 6) {
            let fio = [];
            if (form.querySelector('[data-code="COMPANY_UF_COMPANY_NAME"]')) fio[0] = form.querySelector('[data-code="COMPANY_UF_COMPANY_NAME"]').value;
            if (form.querySelector('[data-code="COMPANY_UF_COMPANY_LAST_NAME"]')) fio[1] = form.querySelector('[data-code="COMPANY_UF_COMPANY_LAST_NAME"]').value;
            if (form.querySelector('[data-code="COMPANY_UF_COMPANY_SECOND_NAME"]')) fio[2] = form.querySelector('[data-code="COMPANY_UF_COMPANY_SECOND_NAME"]').value;
            if (fio) {
                nameProfile.value = fio.join(' ');
            }

        }
    }


    form.submit();
};
