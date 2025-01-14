function JCSmartFilter(ajaxURL, viewMode, params) {
    this.ajaxURL = ajaxURL;
    this.form = null;
    this.timer = null;
    this.cacheKey = '';
    this.cache = [];
    this.popups = [];
    this.viewMode = viewMode;
    this.needReload = false;
    this.loader = BX('fullscreenLoader');
    if (params && params.SEF_SET_FILTER_URL) {
        this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
        this.sef = true;
    }
    if (params && params.SEF_DEL_FILTER_URL) {
        this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
    }
    this.bindUncheck();
}

JCSmartFilter.prototype.bindUncheck = function (checkbox) {
    let rows = document.querySelectorAll('.filter-unchecked'),
        filterItem;

    if (rows) {
        rows.forEach(row => {
            BX.bind(row, 'click', BX.delegate(function () {
                filterItem = BX(row.dataset.id);
                this.needReload = true;
                this.loader.classList.add("show");
                BX.fireEvent(filterItem, 'click');
            }, this));

        });
    }
};

JCSmartFilter.prototype.click = function (checkbox) {
    if (!!this.timer) {
        clearTimeout(this.timer);
    }

    this.timer = setTimeout(BX.delegate(function () {
        this.reload(checkbox);
    }, this), 500);
};

JCSmartFilter.prototype.reload = function (input) {
    if (this.cacheKey !== '') {
        //Postprone backend query
        if (!!this.timer) {
            clearTimeout(this.timer);
        }
        this.timer = setTimeout(BX.delegate(function () {
            this.reload(input);
        }, this), 1000);
        return;
    }
    this.cacheKey = '|';

    this.position = BX.pos(input, true);
    this.form = BX.findParent(input, {'tag': 'form'});
    if (this.form) {
        var values = [];
        values[0] = {name: 'ajax', value: 'y'};
        this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

        for (var i = 0; i < values.length; i++)
            this.cacheKey += values[i].name + ':' + values[i].value + '|';

        if (this.cache[this.cacheKey]) {
            this.curFilterinput = input;
            this.postHandler(this.cache[this.cacheKey], true);
        }
        else {
            if (this.sef) {
                var set_filter = BX('set_filter');
                set_filter.disabled = true;
            }

            this.curFilterinput = input;
            BX.ajax.loadJSON(
                this.ajaxURL,
                this.values2post(values),
                BX.delegate(this.postHandler, this)
            );
        }
    }
};

JCSmartFilter.prototype.updateItem = function (PID, arItem) {
    if (arItem.VALUES) {
        for (var i in arItem.VALUES) {
            if (arItem.VALUES.hasOwnProperty(i)) {
                var value = arItem.VALUES[i];
                var control = BX(value.CONTROL_ID);

                if (!!control) {
                    var label = document.querySelector('[data-role="label_' + value.CONTROL_ID + '"]');
                    if (value.DISABLED) {
                        BX.adjust(control, {props: {disabled: true}});
                        if (label)
                            BX.addClass(label, 'disabled');
                        else
                            BX.addClass(control.parentNode, 'disabled');
                    }
                    else {
                        BX.adjust(control, {props: {disabled: false}});
                        if (label)
                            BX.removeClass(label, 'disabled');
                        else
                            BX.removeClass(control.parentNode, 'disabled');
                    }
                }
            }
        }
    }
};

JCSmartFilter.prototype.postHandler = function (result, fromCache) {

    if (!!result && !!result.ITEMS) {
        for (var popupId in this.popups) {
            if (this.popups.hasOwnProperty(popupId)) {
                this.popups[popupId].destroy();
            }
        }
        this.popups = [];

        for (var PID in result.ITEMS) {
            if (result.ITEMS.hasOwnProperty(PID)) {
                this.updateItem(PID, result.ITEMS[PID]);
            }
        }

        if (result.SEF_SET_FILTER_URL) {
            this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
        }
    }

    if (this.sef) {
        var set_filter = BX('set_filter');
        set_filter.disabled = false;
        set_filter.focus();
    }

    if (!fromCache && this.cacheKey !== '') {
        this.cache[this.cacheKey] = result;
    }
    this.cacheKey = '';
    if (this.needReload){
        this.needReload = false;
        window.location.href = result.SEF_SET_FILTER_URL;
    }
};

JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url) {
    var button = BX(buttonId),
        loader =  this.loader;

    if (button) {
        var proxy = function (j, k,func) {
            return function () {
                return func(j, k);
            }
        };

        if (button.type == 'submit')
            button.type = 'button';

        BX.bind(button, 'click', proxy(url,loader,function (url,loader) {
            loader.classList.add("show");
            window.location.href = url;
            return false;
        }));
    }
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements) {
    if (elements) {
        for (var i = 0; i < elements.length; i++) {
            var el = elements[i];
            if (el.disabled || !el.type)
                continue;

            switch (el.type.toLowerCase()) {
                case 'text':
                case 'textarea':
                case 'password':
                case 'hidden':
                case 'number':
                case 'phone':
                case 'email':
                case 'select-one':
                    if (el.value.length)
                        values[values.length] = {name: el.name, value: el.value};
                    break;
                case 'radio':
                case 'checkbox':
                    if (el.checked)
                        values[values.length] = {name: el.name, value: el.value};
                    break;
                case 'select-multiple':
                    for (var j = 0; j < el.options.length; j++) {
                        if (el.options[j].selected)
                            values[values.length] = {name: el.name, value: el.options[j].value};
                    }
                    break;
                default:
                    break;
            }
        }
    }
};

JCSmartFilter.prototype.values2post = function (values) {
    var post = [];
    var current = post;
    var i = 0;

    while (i < values.length) {
        var p = values[i].name.indexOf('[');
        if (p == -1) {
            current[values[i].name] = values[i].value;
            current = post;
            i++;
        }
        else {
            var name = values[i].name.substring(0, p);
            var rest = values[i].name.substring(p + 1);
            if (!current[name])
                current[name] = [];

            var pp = rest.indexOf(']');
            if (pp == -1) {
                //Error - not balanced brackets
                current = post;
                i++;
            }
            else if (pp == 0) {
                //No index specified - so take the next integer
                current = current[name];
                values[i].name = '' + current.length;
            }
            else {
                //Now index name becomes and name and we go deeper into the array
                current = current[name];
                values[i].name = rest.substring(0, pp) + rest.substring(pp + 1);
            }
        }
    }
    return post;
};
