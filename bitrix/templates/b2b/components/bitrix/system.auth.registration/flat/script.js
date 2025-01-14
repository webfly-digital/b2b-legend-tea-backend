(function () {
    'use strict';

    if (!!window.B2BRegister)
        return;

    window.B2BRegister = function () {
        this.lastCall = '';
        this.lastCallTimer = '';
    };

    window.B2BRegister.prototype = {
        init: function (params) {
            this.loader = document.querySelector('#fullscreenLoader');
            this.ajaxUrl = params.ajaxUrl;
            this.innField = BX('INN_GET');
            this.innNote = BX('INN_NOTE');
            this.company = BX('inn_note-company');
            this.companyAdr = BX('inn_note-company_adr');
            this.companyRow = BX('COMPANY_ROW');
            this.companyAdrRow = BX('COMPANY_ADR_ROW');
            this.existUser = BX('EXIST_USER_NOTE');
            this.emailUsers = BX('email_users');
            this.registerBtn = BX('REGISTER_BTN');
            this.individual = BX('INDIVIDUAL');

            let suggest = () => this.suggest();

            BX.bind(this.innField, 'input', BX.proxy(function () {
                this.debounce(suggest, 1500);
            }, this));

            BX.bind(this.individual, 'change', BX.proxy(function () {
                var target = BX.proxy_context;

                if (target.checked) {
                    if (this.registerBtn.hasAttribute('disabled')) {
                        this.registerBtn.removeAttribute('disabled')
                    }
                } else {
                    if (this.existUser.style.display !== 'none') {
                        this.registerBtn.setAttribute('disabled', 'disabled')
                    }
                }
            }, this));
        },
        debounce: function (f, t) {
            let previousCall = this.lastCall;
            this.lastCall = Date.now();
            if (previousCall != '' && ((this.lastCall - previousCall) <= t)) {
                clearTimeout(this.lastCallTimer);
            }
            this.lastCallTimer = setTimeout(() => f(), t);
        },
        suggest: function () {
            this.showLoader();
            let value = this.innField.value;
            BX.ajax({
                method: 'POST',
                dataType: 'json',
                url: this.ajaxUrl,
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
        },
        hideFields: function (response) {
            if (response.data.COMPANY) {
                BX.show(this.innNote);
                BX.hide(this.companyRow);
                this.companyRow.querySelector('input').value = this.company.innerText = response.data.COMPANY;
            }
            if (response.data.COMPANY_ADR) {
                BX.hide(this.companyAdrRow);
                this.companyAdrRow.querySelector('input').value = this.companyAdr.innerText = response.data.COMPANY_ADR;
            }

            if (response.data.EXIST_INN) {
                this.emailUsers.innerHTML = response.data.EXIST_INN;
                BX.show(this.existUser);
                this.registerBtn.setAttribute('disabled', 'disabled')
            }
        },
        showFields: function () {
            this.companyRow.querySelector('input').value = this.company.innerText = '';
            this.companyAdrRow.querySelector('input').value = this.companyAdr.innerText = '';
            BX.hide(this.innNote);
            BX.show(this.companyRow);
            BX.show(this.companyAdrRow);

            this.emailUsers.innerText = '';
            BX.hide(this.existUser);
            if (this.registerBtn.hasAttribute('disabled')) {
                this.registerBtn.removeAttribute('disabled')
            }
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
