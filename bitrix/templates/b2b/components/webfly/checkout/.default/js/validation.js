let errorMes = {
    required: 'Поле не должно быть пустым',
    phone: 'Неправильный номер',
    email: 'Некорректный email',
    minlength: 'Мин. количество символов: ',
    maxlength: 'Макс. количество символов: ',
};

const regEmail = /^\s*[\w.-]+@[\w-]+\.[A-Za-z]{2,8}\s*$/;

const checkValue = (target) => {
    target.classList.add('validate');
    if (!target.value && target.required) {
        target.setCustomValidity(errorMes.required);
        return;
    }
    if (target.dataset.minlength && target.value.length < target.dataset.minlength){
        target.setCustomValidity(errorMes.minlength+target.dataset.minlength);
        return;
    }
    if (target.dataset.maxlength && target.value.length > target.dataset.maxlength){
        target.setCustomValidity(errorMes.maxlength+target.dataset.maxlength);
        return;
    }
    if (target.dataset.validate === 'phone') {
        let phoneLength = target.value.replace(/\D/g, '').length;
        if (target.value && phoneLength < 11) {
            target.setCustomValidity(errorMes.phone);
            return;
        }
    }
    if (target.dataset.validate === 'email') {
        target.value = target.value.trim();
        if (target.value && !regEmail.test(target.value)) {
            target.setCustomValidity(errorMes.email);
        }
        return;
    }
};

const onFocusForm = ({target}) => {
    if (target.required) {
        target.setCustomValidity('');
        if (target.dataset.validate === 'phone') {
            onFocusPhoneInput(target);
        }
        if (target.dataset.validate === 'number') {
            onFocusNumberInput(target);
        }
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

const COUNTRY_CODE = '+7';
const onInputPhoneInput = ({target}) => {
    const matrix = `${COUNTRY_CODE} (___) ___-__-__`;
    const def = matrix.replace(/\D/g, '');
    let i = 0;
    let val = target.value.replace(/\D/g, '');
    if (!val.length) {
        val = def;
    }

    target.value = '';
    Array.prototype.forEach.call(matrix, (item) => {
        let isValNumber = /[_\d]/.test(item) && val.length > i;
        if (isValNumber) {
            target.value += val.charAt(i++);
        } else {
            target.value += val.length <= i ? '' : item;
        }
    });
};
const onFocusPhoneInput = (target) => {
    target.addEventListener('input', onInputPhoneInput);
    target.addEventListener('blur', onBlurPhoneInput);
};
const onBlurPhoneInput = ({target}) => {
    target.removeEventListener('input', onInputPhoneInput);
    target.removeEventListener('blur', onBlurPhoneInput);
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
const reInitForm = function (form) {
    form.removeEventListener('click', preSubmit);
    form.removeEventListener('focusin', onFocusForm);
    form.removeEventListener('submit', onSubmit);
    form.removeEventListener('focusout', onBlurForm);

    const inputs = form.querySelectorAll('[required]');
    inputs.forEach((input) => {
        input.classList.remove('validate');
    });
    initForm(form);
};


const onFocusNumberInput = (input) => {
    const onInputNumberInput = ({target}) => {
        target.value = target.value.replace(/\D/, '');
    };

    const onBlurNumberInput = ({target}) => {
        target.removeEventListener('input', onInputNumberInput);
        target.removeEventListener('blur', onBlurNumberInput);
    };

    input.addEventListener('input', onInputNumberInput);
    input.addEventListener('blur', onBlurNumberInput);
};

const sendData = (form) => {
    if (BX.WebflyCheckoutComponent) BX.WebflyCheckoutComponent.sendRequest('saveOrderAjax');
};
