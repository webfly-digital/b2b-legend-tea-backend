function BasketToCatalogUpdater() {
    this.items = null;
    this.basketUrl = '';
    this.searchUserUrl = '';

    BX.addCustomEvent("OnShowCatalogItems", BX.delegate(function () {
        this.refreshCatalogQuantity();
    }, this));

}

BasketToCatalogUpdater.prototype.init = function (params) {
    this.items = params.items || {};
    this.basketUrl = params.basketUrl || '';
    this.searchUserUrl = params.searchUserUrl || '';

    this.refreshCatalogQuantity();
    this.initDeleteAllAction();

    let inputSearch = BX('search_user');
    if (inputSearch) BX.bind(inputSearch, 'input', BX.delegate(this.searchUser, this));
};
BasketToCatalogUpdater.prototype.refreshCatalogQuantity = function () {
    let data = this.items || {};

    if (!Array.isArray(data)) {
        let productQuantityItem;
        for (let pId in data) {
            productQuantityItem = document.querySelector('[data-entity="quantity-block"][data-product="' + pId + '"] [name="quantity"]');
            if (productQuantityItem && productQuantityItem.value != data[pId]) {
                productQuantityItem.value = data[pId];
                BX.fireEvent(productQuantityItem, 'basketchange');
            }
        }
    } else {
        let productQuantityItems = document.querySelectorAll('[data-entity="quantity-block"] [name="quantity"]');
        if (productQuantityItems) {
            productQuantityItems.forEach(item => {
                if (item.value !== 0) {
                    item.value = 0
                    BX.fireEvent(item, 'basketchange');
                }
            });
        }
    }
};
BasketToCatalogUpdater.prototype.initDeleteAllAction = function () {
    this.deleteAllBtn = BX('deleteAll');
    BX.bind(this.deleteAllBtn, 'click', BX.delegate(this.deleteAll, this));
};
BasketToCatalogUpdater.prototype.searchUser = function () {
    let target = BX.proxy_context;
    let data = {};
    data.sessid = BX.bitrix_sessid();
    data.action = 'searchUser';
    data.str = target.value;
    if (target && target.value.length > 2) {
        BX.ajax({
            method: 'POST',
            dataType: 'json',
            url: this.searchUserUrl,
            data: data,
            onsuccess: function (data) {
                let blockResponse = BX('blockResponse');
                blockResponse.innerHTML = '';
                if (typeof (data.response) == 'object') {
                    data.response.forEach(function (item) {
                        var div = document.createElement('div');
                        div.setAttribute('data-id', item.ID);
                        div.innerText = item.NAME;
                        blockResponse.appendChild(div);

                        BX.bind(div, 'click', function () {
                            let dataNew = {};
                            let id = this.getAttribute('data-id');
                            if (id) {
                                dataNew.sessid = BX.bitrix_sessid();
                                dataNew.action = 'authUser';
                                dataNew.id = id;
                                BX.ajax({
                                    method: 'POST',
                                    dataType: 'json',
                                    url: '/bitrix/templates/b2b/ajax/searchUser.php',
                                    data: dataNew,
                                    onsuccess: function (data) {
                                        location.href = location.pathname;
                                    },
                                    onfailure: function () {
                                    }
                                });
                            }
                        });
                    })
                } else if (typeof (data.response) == 'string') {
                    blockResponse.innerHTML = '<div>' + data.response + '</div>';
                }
            },
            onfailure: function () {
            }
        });
    }
};

BasketToCatalogUpdater.prototype.deleteAll = function () {
    let data = {};
    data.sessid = BX.bitrix_sessid();
    data.action = 'deleteAll';
    BX.ajax({
        method: 'POST',
        dataType: 'json',
        url: this.basketUrl,
        data: data,
        onsuccess: BX.delegate(function () {
            BX.onCustomEvent('OnBasketChange');
        }, this)
    });
};
