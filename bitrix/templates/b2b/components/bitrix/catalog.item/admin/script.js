(function (window) {
    'use strict';

    if (window.JCCatalogItem)
        return;

    window.JCCatalogItem = function (arParams) {
        this.productType = 0;

        this.visual = {
            ID: '',
            QUANTITY_ID: '',
            QUANTITY_UP_ID: '',
            QUANTITY_DOWN_ID: '',
            PRICE_ID: '',
        };

        this.product = {
            checkQuantity: false,
            maxQuantity: 0,
            stepQuantity: 1,
            canBuy: true,
            name: '',
            id: 0,
        };

        this.basketData = {
            quantity: 'quantity',
            props: 'prop',
            sku_props: '',
            sku_props_var: 'basket_props'
        };

        this.fullDisplayMode = false;

        this.currentPrices = [];
        this.currentPriceSelected = 0;

        this.checkQuantity = false;
        this.stepQuantity = 1;
        this.maxQuantity = 0;
        this.minQuantity = 0;
        this.canBuy = true;

        this.offers = [];
        this.treeProps = [];
        this.selectedValues = {};

        this.blockNodes = {};
        this.obProduct = null;
        this.obPrice = null;
        this.obQuantity = null;
        this.obQuantityUp = null;
        this.obQuantityDown = null;
        this.obTree = null;
        this.obNotAvail = null;

        this.basketUrl = '';

        this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');

        this.quantityDelay = null;
        this.quantityTimer = null;
        this.errorCode = 0;


        if (typeof arParams === 'object') {

            if (arParams.PRODUCT_TYPE) {
                this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
            }
            this.fullDisplayMode = arParams.PRODUCT_DISPLAY_MODE === 'Y';
            this.visual = arParams.VISUAL;

            switch (this.productType) {
                case 0: // no catalog
                case 1: // product
                case 2: // set
                    if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object') {
                        /**
                         * Prices
                         */
                        this.currentPrices = arParams.PRODUCT.ITEM_PRICES;
                        this.currentPriceSelected = arParams.PRODUCT.ITEM_PRICE_SELECTED;
                        /**
                         * Quantity
                         */
                        this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
                        if (this.product.checkQuantity)
                            this.product.maxQuantity = parseInt(arParams.PRODUCT.MAX_QUANTITY, 10);
                        this.product.stepQuantity = parseInt(arParams.PRODUCT.STEP_QUANTITY, 10);
                        this.checkQuantity = this.product.checkQuantity;
                        this.stepQuantity = this.product.stepQuantity;
                        this.maxQuantity = this.product.maxQuantity;

                        this.canBuy = this.product.canBuy;
                        this.product.name = arParams.PRODUCT.NAME;
                        this.product.id = arParams.PRODUCT.ID;
                        this.product.parent_id = arParams.PRODUCT.PARENT_ID;

                    } else {
                        this.errorCode = -1;
                    }
                    break;
                case 3: // sku
                    if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object') {
                        this.product.name = arParams.PRODUCT.NAME;
                        this.product.id = arParams.PRODUCT.ID;
                    }
                    if (arParams.OFFERS && BX.type.isArray(arParams.OFFERS)) {
                        this.offers = arParams.OFFERS;

                        if (arParams.OFFER_SELECTED) {
                            this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
                        }

                        if (isNaN(this.offerNum)) {
                            this.offerNum = 0;
                        }

                        if (arParams.TREE_PROPS) {
                            this.treeProps = arParams.TREE_PROPS;
                        }
                    }
                    break;
                default:
                    this.errorCode = -1;
            }
            /**
             * Basket variables
             */
            if (arParams.BASKET && typeof arParams.BASKET === 'object') {
                if (arParams.BASKET.QUANTITY)
                    this.basketData.quantity = arParams.BASKET.QUANTITY;

                if (arParams.BASKET.PROPS)
                    this.basketData.props = arParams.BASKET.PROPS;

                if (arParams.BASKET.ADD_TO_BASKET_URL)
                    this.basketUrl = arParams.BASKET.ADD_TO_BASKET_URL;

                if (3 === this.productType) {//sku
                    if (arParams.BASKET.SKU_PROPS)
                        this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
                }

                if (arParams.BASKET.ADD_URL_TEMPLATE)
                    this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;

                if (arParams.BASKET.BUY_URL_TEMPLATE)
                    this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;

                if (this.basketData.add_url === '' && this.basketData.buy_url === '')
                    this.errorCode = -1024;
            }

        }
        BX.ready(BX.delegate(this.init, this));
    };

    window.JCCatalogItem.prototype = {
        init: function () {
            if (this.productType === 3) //sku
                this.obProduct = BX(this.visual.TREE_ID);
            else
                this.obProduct = BX(this.visual.ID);

            if (!this.obProduct) {
                this.errorCode = -1;
                return;
            }

            this.obPrice = BX(this.visual.PRICE_ID);
            if (!this.obPrice)
                this.errorCode = -16;

            this.blockNodes.price = this.obProduct.querySelector('[data-entity="price-block"]');

            if (this.visual.QUANTITY_ID) {
                this.blockNodes.quantity = this.obProduct.querySelector('[data-entity="quantity-block"]');

                this.obQuantity = BX(this.visual.QUANTITY_ID);

                if (this.visual.QUANTITY_UP_ID)
                    this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);

                if (this.visual.QUANTITY_DOWN_ID)
                    this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
            }

            if (this.productType === 3 && this.fullDisplayMode) {//sku
                if (this.visual.TREE_ID) {
                    this.obTree = BX(this.visual.TREE_ID);
                    if (!this.obTree) {
                        this.errorCode = -256;
                    }
                }
            }

            this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);

            if (this.errorCode === 0) {
                let treeItems, i;

                var startEventName = this.isTouchDevice ? 'touchstart' : 'mousedown';
                var endEventName = this.isTouchDevice ? 'touchend' : 'mouseup';

                if (this.obQuantityUp) {
                    // BX.bind(this.obQuantityUp, startEventName, BX.proxy(this.startQuantityInterval, this));
                    // BX.bind(this.obQuantityUp, endEventName, BX.proxy(this.clearQuantityInterval, this));
                    // BX.bind(this.obQuantityUp, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
                    BX.bind(this.obQuantityUp, 'click', BX.delegate(this.quantityUp, this));
                }

                if (this.obQuantityDown) {
                    // BX.bind(this.obQuantityDown, startEventName, BX.proxy(this.startQuantityInterval, this));
                    // BX.bind(this.obQuantityDown, endEventName, BX.proxy(this.clearQuantityInterval, this));
                    // BX.bind(this.obQuantityDown, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
                    BX.bind(this.obQuantityDown, 'click', BX.delegate(this.quantityDown, this));
                }

                if (this.obQuantity) {
                    BX.bind(this.obQuantity, 'change', BX.delegate(this.quantityChange, this));
                    BX.bind(this.obQuantity, 'basketchange', BX.delegate(this.checkQuantityControls, this));
                }

                switch (this.productType) {
                    case 0: // no catalog
                    case 1: // product
                    case 2: // set
                        this.checkQuantityControls();
                        break;
                    case 3: // sku
                        if (this.offers.length > 0) {
                            treeItems = BX.findChildren(this.obTree, {className: 'sku-change'}, true);
                            if (treeItems) {
                                treeItems.forEach(item => {
                                    BX.bind(item, 'change', BX.delegate(this.selectOfferProp, this));
                                });
                            }
                            this.setCurrent();
                        }
                        break;
                }
            }
        },
        // startQuantityInterval: function () {
        //     var target = BX.proxy_context;
        //     var func = target.id === this.visual.QUANTITY_DOWN_ID
        //         ? BX.proxy(this.quantityDown, this)
        //         : BX.proxy(this.quantityUp, this);
        //
        //     this.quantityDelay = setTimeout(
        //         BX.delegate(function () {
        //             this.quantityTimer = setInterval(func, 150);
        //         }, this),
        //         300
        //     );
        // },
        clearQuantityInterval: function () {
            clearTimeout(this.quantityDelay);
            clearInterval(this.quantityTimer);
        },
        quantityUp: function () {
            BX.addClass(this.obQuantityUp, 'disabled');
            if (this.quntityDetail) BX.addClass(this.quntityDetail.obQuantityUp, 'disabled');

            var curValue = 0,
                isCorrectQuantity = true;
            if (this.errorCode === 0 && this.canBuy) {
                curValue = parseInt(this.obQuantity.value, 10);

                if (!isNaN(curValue)) {
                    if (this.checkQuantity) {
                        if (curValue >= this.maxQuantity)
                            isCorrectQuantity = false;
                    }

                    if (isCorrectQuantity) {
                        this.obQuantity.value = curValue + this.stepQuantity;
                        BX.fireEvent(this.obQuantity, 'change');
                    }
                }
            }
            if (this.quntityDetail) this.quntityDetail.obQuantity.value = this.obQuantity.value;
        },
        quantityDown: function () {
            var target = BX.proxy_context;
            BX.addClass(this.obQuantityDown, 'disabled');
            if (this.quntityDetail) BX.addClass(this.quntityDetail.obQuantityDown, 'disabled');

            var curValue = 0,
                isCorrectQuantity = true;

            if (this.errorCode === 0 && this.canBuy) {
                curValue = parseInt(this.obQuantity.value, 10);

                if (!isNaN(curValue)) {
                    if (curValue <= this.minQuantity) {
                        isCorrectQuantity = false;
                    }

                    if (isCorrectQuantity) {
                        this.obQuantity.value = curValue - this.stepQuantity;
                        BX.fireEvent(this.obQuantity, 'change');
                    }
                }
            }

            if (this.quntityDetail) this.quntityDetail.obQuantity.value = this.obQuantity.value;

        },
        quantityChange: function () {
            var target = BX.proxy_context;
            var curValue = 0;

            if (this.errorCode === 0) {
                if (this.canBuy) {
                    curValue = Math.round(this.obQuantity.value);
                    if (this.quntityDetail && target && target.value == this.quntityDetail.obQuantity.value) curValue = Math.round(this.quntityDetail.obQuantity.value);

                    if (!isNaN(curValue)) {
                        if (this.checkQuantity) {
                            if (curValue > this.maxQuantity) {
                                curValue = this.maxQuantity;
                            }
                        }
                        if (curValue < this.minQuantity) {
                            curValue = this.minQuantity;
                        }
                        this.obQuantity.value = curValue;
                    } else {
                        this.obQuantity.value = this.minQuantity;
                    }
                } else {
                    this.obQuantity.value = this.minQuantity;
                }

                if (this.quntityDetail) this.quntityDetail.obQuantity.value = this.obQuantity.value;

                this.addToBasket();
                this.checkQuantityControls();
            }
        },
        addToBasket: function () {
            if (!this.canBuy) {
                return;
            }

            let data = {};

            switch (this.productType) {
                case 1: // product
                case 2: // set
                    data.ID = this.product.id.toString();
                    break;
                case 3: // sku
                    data.ID = this.offers[this.offerNum].ID;
                    break;
            }
            data.QUANTITY = this.obQuantity.value;
            data.action = 'add';
            data.sessid = BX.bitrix_sessid();

            BX.ajax({
                method: 'POST',
                dataType: 'json',
                url: this.basketUrl,
                data: data,
                onsuccess: BX.proxy(this.basketResult, this)
            });
        },
        basketResult: function (arResult) {
            BX.onCustomEvent('OnBasketChange');
        },
        checkQuantityControls: function () {
            if (!this.obQuantity) return;

            var reachedTopLimit = this.checkQuantity && parseFloat(this.obQuantity.value) >= this.maxQuantity,
                reachedBottomLimit = parseFloat(this.obQuantity.value) <= this.minQuantity;

            if (reachedTopLimit) {
                BX.addClass(this.obQuantityUp, 'disabled');
                if (this.quntityDetail) BX.addClass(this.quntityDetail.obQuantityUp, 'disabled');
            } else if (BX.hasClass(this.obQuantityUp, 'disabled')) {
                BX.removeClass(this.obQuantityUp, 'disabled');
                if (this.quntityDetail && BX.hasClass(this.quntityDetail.obQuantityUp, 'disabled')) BX.removeClass(this.quntityDetail.obQuantityUp, 'disabled');
            }

            if (reachedBottomLimit) {
                BX.addClass(this.obQuantityDown, 'disabled');
                if (this.quntityDetail) BX.addClass(this.quntityDetail.obQuantityDown, 'disabled');
            } else if (BX.hasClass(this.obQuantityDown, 'disabled')) {
                BX.removeClass(this.obQuantityDown, 'disabled');

                if (this.quntityDetail && BX.hasClass(this.quntityDetail.obQuantityDown, 'disabled')) BX.removeClass(this.quntityDetail.obQuantityDown, 'disabled');
            }

            if (reachedTopLimit && reachedBottomLimit) {
                this.obQuantity.setAttribute('disabled', 'disabled');
                if (this.quntityDetail) this.quntityDetail.obQuantity.setAttribute('disabled', 'disabled');
            } else {
                this.obQuantity.removeAttribute('disabled');
                if (this.quntityDetail && this.quntityDetail.obQuantity) this.quntityDetail.obQuantity.removeAttribute('disabled');
            }

        },
        quantitySet: function (index) {
            var newOffer = this.offers[index], price;

            if (this.errorCode === 0) {
                this.canBuy = newOffer.CAN_BUY;

                this.currentPrices = newOffer.ITEM_PRICES;
                this.currentPriceSelected = newOffer.ITEM_PRICE_SELECTED;
                price = this.currentPrices[this.currentPriceSelected];

                if (this.canBuy && price['PRICE'] && price['PRICE'] != 0) {
                    if (this.blockNodes.quantity)
                        BX.removeClass(this.blockNodes.quantity, 'disabled');

                    if (this.obQuantity) {
                        this.obQuantity.removeAttribute('disabled');
                        if (this.quntityDetail) this.quntityDetail.obQuantity.removeAttribute('disabled');
                    }

                } else {
                    if (this.blockNodes.quantity)
                        BX.addClass(this.blockNodes.quantity, 'disabled');

                    if (this.obQuantity) {
                        this.obQuantity.setAttribute('disabled', 'disabled');
                        if (this.quntityDetail) this.quntityDetail.obQuantity.setAttribute('disabled', 'disabled');
                    }
                }
                this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
                this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);

                if (BasketToCatalogUpdater.items.hasOwnProperty(newOffer.ID)) {
                    this.obQuantity.value = BasketToCatalogUpdater.items[newOffer.ID];
                } else {
                    this.obQuantity.value = this.minQuantity;
                }
                if (this.quntityDetail) this.quntityDetail.obQuantity.value = this.obQuantity.value;

                this.blockNodes.quantity.dataset.product = newOffer.ID;
            }

        },
        setPrice: function () {
            var price, priceFormat;

            this.checkQuantityControls();

            price = this.currentPrices[this.currentPriceSelected];

            if (this.obPrice) {
                if (this.canBuy && price['PRICE'] && price['PRICE'] != 0) {
                    priceFormat = BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true);
                    priceFormat = priceFormat.replace('руб.', '₽');
                    BX.adjust(this.obPrice, {html: priceFormat});
                    if (this.quntityDetail && this.quntityDetail.obPrice) BX.adjust(this.quntityDetail.obPrice, {html: priceFormat})
                    BX.removeClass(this.blockNodes.price, 'disabled');
                } else {
                    BX.adjust(this.obPrice, {html: '-'});
                    if (this.quntityDetail && this.quntityDetail.obPrice) BX.adjust(this.quntityDetail.obPrice, {html: '-'})
                    BX.addClass(this.blockNodes.price, 'disabled');
                }
            }

        },
        getRowValues: function (arFilter, index) {
            var i = 0,
                j,
                arValues = [],
                boolSearch = false,
                boolOneSearch = true;

            if (0 === arFilter.length) {
                for (i = 0; i < this.offers.length; i++) {
                    if (!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
                        arValues[arValues.length] = this.offers[i].TREE[index];
                    }
                }
                boolSearch = true;
            } else {
                for (i = 0; i < this.offers.length; i++) {
                    boolOneSearch = true;
                    for (j in arFilter) {
                        if (arFilter[j] !== this.offers[i].TREE[j]) {
                            boolOneSearch = false;
                            break;
                        }
                    }
                    if (boolOneSearch) {
                        if (!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
                            arValues[arValues.length] = this.offers[i].TREE[index];
                        }
                        boolSearch = true;
                    }
                }
            }
            return (boolSearch ? arValues : false);
        },
        getCanBuy: function (arFilter) {
            var i, j,
                boolSearch = false,
                boolOneSearch = true;

            for (i = 0; i < this.offers.length; i++) {
                boolOneSearch = true;
                for (j in arFilter) {
                    if (arFilter[j] !== this.offers[i].TREE[j]) {
                        boolOneSearch = false;
                        break;
                    }
                }
                if (boolOneSearch) {
                    if (this.offers[i].CAN_BUY) {
                        boolSearch = true;
                        break;
                    }
                }
            }

            return boolSearch;
        },
        setCurrent: function () {
            var i,
                j = 0,
                arCanBuyValues = [],
                strName = '',
                arShowValues = false,
                arFilter = {},
                tmpFilter = [],
                current = this.offers[this.offerNum].TREE;

            for (i = 0; i < this.treeProps.length; i++) {
                strName = 'PROP_' + this.treeProps[i].ID;
                arShowValues = this.getRowValues(arFilter, strName);
                if (!arShowValues) {
                    break;
                }
                if (BX.util.in_array(current[strName], arShowValues)) {
                    arFilter[strName] = current[strName];
                } else {
                    arFilter[strName] = arShowValues[0];
                    this.offerNum = 0;
                }
                arCanBuyValues = [];
                tmpFilter = [];
                tmpFilter = BX.clone(arFilter, true);
                for (j = 0; j < arShowValues.length; j++) {
                    tmpFilter[strName] = arShowValues[j];
                    if (this.getCanBuy(tmpFilter)) {
                        arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
                    }
                }
            }
            this.selectedValues = arFilter;
            this.changeInfo();
        },
        changeInfo: function () {
            var i, j,
                index = -1,
                boolOneSearch = true;

            for (i = 0; i < this.offers.length; i++) {
                boolOneSearch = true;
                for (j in this.selectedValues) {
                    if (this.selectedValues[j] !== this.offers[i].TREE[j]) {
                        boolOneSearch = false;
                        break;
                    }
                }
                if (boolOneSearch) {
                    index = i;
                    break;
                }
            }
            if (index > -1) {
                this.quantitySet(index);
                this.setPrice();
                this.offerNum = index;
            }
        },
        selectOfferProp: function () {
            let i = 0,
                value = '',
                target = BX.proxy_context,
                strTreeValue = '',
                selected,
                rowItems = null,
                arTreeItem = [];
            if (target) {
                selected = target.options[target.selectedIndex];

                if (selected.hasAttribute('data-treevalue')) strTreeValue = selected.getAttribute('data-treevalue');
                else if (selected.hasAttribute('data-custom-properties')) {
                    strTreeValue = selected.getAttribute('data-custom-properties');
                    if (this.quntityDetail) {
                        let elemOffersTable = this.obProduct.querySelectorAll('[data-custom-properties]')
                        if (elemOffersTable) {
                            elemOffersTable.forEach((offer, key) => {
                                value = offer.getAttribute('data-custom-properties');
                                if (value === strTreeValue) {
                                    elemOffersTable[key].setAttribute('selected', 'selected');
                                } else {
                                    elemOffersTable[key].removeAttribute('selected', 'selected');
                                }
                            })
                        }

                    }
                }
                arTreeItem = strTreeValue.split('_');

                if (this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1])) {
                    rowItems = BX.findChildren(target, {tagName: 'option'}, false);

                    if (rowItems && 0 < rowItems.length) {
                        for (i = 0; i < rowItems.length; i++) {
                            if (selected.hasAttribute('data-onevalue')) {
                                value = rowItems[i].getAttribute('data-onevalue');
                                if (value === arTreeItem[1]) {
                                    rowItems[i].setAttribute('selected', 'selected');
                                } else {
                                    rowItems[i].removeAttribute('selected', 'selected');
                                }
                            } else {
                                if (rowItems[i].value && rowItems.length == 1) {
                                    value = rowItems[i].value;
                                    if (value === arTreeItem[1]) {
                                        rowItems[i].setAttribute('selected', 'selected');
                                    } else {
                                        rowItems[i].removeAttribute('selected', 'selected');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },
        searchOfferPropIndex: function (strPropID, strPropValue) {
            let strName = '',
                arShowValues = false,
                i, j,
                arCanBuyValues = [],
                allValues = [],
                index = -1,
                arFilter = {},
                tmpFilter = [];

            for (i = 0; i < this.treeProps.length; i++) {
                if (this.treeProps[i].ID === strPropID) {
                    index = i;
                    break;
                }
            }

            if (-1 < index) {
                for (i = 0; i < index; i++) {
                    strName = 'PROP_' + this.treeProps[i].ID;
                    arFilter[strName] = this.selectedValues[strName];
                }

                strName = 'PROP_' + this.treeProps[index].ID;
                arShowValues = this.getRowValues(arFilter, strName);
                if (!arShowValues) {
                    return false;
                }
                if (!BX.util.in_array(strPropValue, arShowValues)) {
                    return false;
                }
                arFilter[strName] = strPropValue;

                for (i = index + 1; i < this.treeProps.length; i++) {
                    strName = 'PROP_' + this.treeProps[i].ID;
                    arShowValues = this.getRowValues(arFilter, strName);
                    if (!arShowValues) {
                        return false;
                    }
                    allValues = [];
                    if (this.showAbsent) {
                        arCanBuyValues = [];
                        tmpFilter = [];
                        tmpFilter = BX.clone(arFilter, true);
                        for (j = 0; j < arShowValues.length; j++) {
                            tmpFilter[strName] = arShowValues[j];
                            allValues[allValues.length] = arShowValues[j];
                            if (this.getCanBuy(tmpFilter))
                                arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
                        }
                    } else {
                        arCanBuyValues = arShowValues;
                    }
                    if (this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues)) {
                        arFilter[strName] = this.selectedValues[strName];
                    } else {
                        if (this.showAbsent)
                            arFilter[strName] = (arCanBuyValues.length > 0 ? arCanBuyValues[0] : allValues[0]);
                        else
                            arFilter[strName] = arCanBuyValues[0];
                    }
                }
                this.selectedValues = arFilter;
                this.changeInfo();
            }

            return true;

        },
    };

})(window);

(function (window) {
    'use strict';

    if (window.JCCatalogOneItem)
        return;

    window.JCCatalogOneItem = function (arParams) {
        if (typeof arParams === 'object') {
            if (arParams.FAVORITE_BTN) {
                this.product = arParams.PRODUCT;
                this.addToFavBtn = BX(arParams.FAVORITE_BTN);
                this.addToFavBtnDetail = BX(arParams.FAVORITE_BTN + '_detail');
            }
            /**
             * Detail slider
             */
            if (arParams.DETAIL_BTN) {
                this.obDetailBtn = document.querySelectorAll('[data-detail-btn =' + arParams.DETAIL_BTN + ']');
                this.obDetailData = BX(arParams.DETAIL_BLOCK);
            }
            /**
             * Subscribe
             */
            if (arParams.SUBSCRIBE_LINK_HIDDEN)
                this.subscribeBtnId = arParams.SUBSCRIBE_LINK_HIDDEN;

            if (arParams.POPUP_STOCK)
                this.popupStockBtn = document.querySelectorAll('[data-id =' + arParams.POPUP_STOCK + ']');


            if (arParams.DOWNLOAD_PDF_BTN)
                this.downloadPdfBtn = document.querySelectorAll('[data-generate-pdf-btn=' + arParams.DOWNLOAD_PDF_BTN + ']');
        }
        BX.ready(BX.delegate(this.init, this));
    };

    window.JCCatalogOneItem.prototype = {
        init: function () {
            /**
             * Favorites
             */
            this.handlerDetailInit = [];
            if (typeof this.JCCatalogItem == 'object') {
                this.JCCatalogItem.forEach((item, key) => {
                    let handlerDetailInit = {
                        handlerQuantityUpDetail: false,
                        handlerQuantityDownDetail: false,
                        handlerQuantityDetail: false,
                        handlercheckQuantityControlsDetail: false,
                        handlerOffers: false,
                    };
                    this.handlerDetailInit[key] = handlerDetailInit;
                })
            }

            if (this.addToFavBtn) {
                BX.bind(this.addToFavBtn, 'click', BX.delegate(this.addToFavorite, this));
            }
            if (this.addToFavBtnDetail) {
                BX.bind(this.addToFavBtnDetail, 'click', BX.delegate(this.addToFavorite, this));
            }
            /**
             * Detail info
             */
            if (this.obDetailBtn) {
                this.obDetailBtn.forEach(item => {
                    item.addEventListener('click', {handleEvent: this.showDetailInfo, obj: this});
                });
            }

            if (this.downloadPdfBtn) {
                this.downloadPdfBtn.forEach(item => {
                    item.addEventListener('click', BX.delegate(this.generatePdf, this));
                });
            }

            if (this.popupStockBtn && window.innerWidth <= 1200) {
                this.popupStockBtn.forEach(item => {
                    item.addEventListener('click', {handleEvent: this.showPopupStock, obj: this});
                });
            }
            ;
        },
        showPopupStock: function (event) {
            let elemPopup = BX('popupStock');
            let closer = elemPopup.querySelector(".button-full");
            let shade = document.querySelector('.shade');
            BX.show(elemPopup);
            shade.classList.remove('disable');
            BX.bind(closer, 'click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                BX.hide(elemPopup);
                shade.classList.add('disable');
            });
            shade.addEventListener('click', () => {
                BX.hide(elemPopup);
                shade.classList.add('disable');
            });
        },
        showDetailInfo: function (event) {
            event.preventDefault();
            event.stopPropagation();
            let obj = this.obj;
            obj.detailContainer = document.querySelector('.product-slide-content');
            obj.detailContainer.innerHTML = '';
            obj.detailContainer.insertAdjacentElement('afterbegin', obj.obDetailData);
            BX.show(obj.obDetailData);

            if (detailSliderInisialized.indexOf(obj.product.ID) === -1) {
                detailSliderInisialized.push(obj.product.ID);
                initProductDetailSlider();
            }
            obj.initProductDetailSlide();
        },
        initProductDetailSlide: function () {
            let content = document.querySelector(".product-slide-info");
            let closer = content.querySelector(".close");
            let body = document.querySelector("body");
            let shade = document.querySelector(".shade");
            content.classList.add("show");

            this.handlerQuantity(content);

            BX.bind(closer, 'click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                content.classList.remove("show");
                body.classList.remove("noscroll");
                shade.classList.add("disable");
            });
        },
        handlerQuantity: function (content) {
            let obQuantityDown = content.querySelectorAll('[data-quantity-down]');
            let obQuantity = content.querySelectorAll('[data-quantity]');
            let obQuantityUp = content.querySelectorAll('[data-quantity-up]');
            let obPrice = content.querySelectorAll('[data-price]');
            let obOffers = content.querySelectorAll('[data-offers]');
            let obQuantityDetail = {};

            if (typeof this.JCCatalogItem == 'object') {
                this.JCCatalogItem.forEach((item, key) => {
                    obQuantityDetail = {
                        obQuantityUp: obQuantityUp[key],
                        obQuantity: obQuantity[key],
                        obQuantityDown: obQuantityDown[key],
                        obPrice: obPrice[key] ? obPrice[key] : null,
                    };
                    if (obOffers[key]) {
                        let offer = obOffers[key].querySelector('.select');
                        obQuantityDetail.obOffer = offer;
                    }
                    this.JCCatalogItem[key].quntityDetail = obQuantityDetail;

                    if (!this.handlerDetailInit[key].handlerOffers && obQuantityDetail.obOffer) {
                        this.handlerDetailInit[key].handlerOffers = true;

                        let treeItems = BX.findChildren(obQuantityDetail.obOffer, {className: 'sku-change'}, true);
                        if (treeItems) {
                            treeItems.forEach(item => {
                                BX.bind(item, 'change', BX.delegate(function () {
                                    this.JCCatalogItem[key].selectOfferProp()
                                }, this));
                            });
                            this.JCCatalogItem[key].setCurrent();
                        }
                    }


                    if (!this.handlerDetailInit[key].handlercheckQuantityControlsDetail) {
                        this.handlerDetailInit[key].handlercheckQuantityControlsDetail = true;
                        this.JCCatalogItem[key].checkQuantityControls();
                    }

                    if (this.JCCatalogItem[key].quntityDetail.obQuantityUp && !this.handlerDetailInit[key].handlerQuantityUpDetail) {
                        this.handlerDetailInit[key].handlerQuantityUpDetail = true

                        BX.bind(this.JCCatalogItem[key].quntityDetail.obQuantityUp, 'click', BX.delegate(function () {
                            this.JCCatalogItem[key].quantityUp()
                        }, this));
                    }
                    if (this.JCCatalogItem[key].quntityDetail.obQuantityDown && !this.handlerDetailInit[key].handlerQuantityDownDetail) {
                        this.handlerDetailInit[key].handlerQuantityDownDetail = true

                        BX.bind(this.JCCatalogItem[key].quntityDetail.obQuantityDown, 'click', BX.delegate(function () {
                            this.JCCatalogItem[key].quantityDown()
                        }, this));
                    }

                    if (this.JCCatalogItem[key].quntityDetail.obQuantity) {
                        this.JCCatalogItem[key].quntityDetail.obQuantity.value = this.JCCatalogItem[key].obQuantity.value;
                        if (!this.handlerDetailInit[key].handlerQuantityDetail) {
                            this.handlerDetailInit[key].handlerQuantityDetail = true

                            BX.bind(this.JCCatalogItem[key].quntityDetail.obQuantity, 'change', BX.delegate(function () {
                                this.JCCatalogItem[key].quantityChange()
                            }, this));
                        }
                    }
                })
            }
        },
        addToFavorite: function () {
            let data = {productId: this.product.ID};
            var target = BX.proxy_context;
            this.runComponentAction('addToFavorite', data, target);
        },
        generatePdf: function () {
            var target = BX.proxy_context;
            var data = {formData: {'ID_ELEM': this.product.ID}};
            let profileID = false;
            if (target) {
                if (target.hasAttribute('data-generate-pdf-profile-id')) {
                    profileID = target.getAttribute('data-generate-pdf-profile-id');
                    if (profileID) data.formData.PROFILE_ID = profileID;
                }
            }

            BX.ajax.runComponentAction('webfly:pdf.product', 'generatePdf', {
                mode: 'class',
                data: data,
            }).then(function (response) {
                if (response.data.hasOwnProperty('product')) {
                    var ob = BX.processHTML(response.data.product);
                    document.body.insertAdjacentHTML('beforeend', ob.HTML);
                    BX.ajax.processScripts(ob.SCRIPT);
                }
            }, function () {
            });
        },
        runComponentAction: function (action, data, target) {
            let sibling;
            if (target.classList.contains('detail-favorite'))
                sibling = this.addToFavBtn;
            else
                sibling = this.addToFavBtnDetail;

            target.classList.add('disabled');
            if (sibling) sibling.classList.add('disabled');
            BX.ajax.runComponentAction('webfly:b2b.favorites', action, {
                mode: 'class',
                data: data,
            }).then(function (response) {
                if (response.data == 'ADD') {
                    target.classList.add('active');
                    sibling.classList.add('active');
                } else if (response.data == 'DELETE') {
                    target.classList.remove('active');
                    sibling.classList.remove('active');
                }
                target.classList.remove('disabled');
                sibling.classList.remove('disabled');
            }, function () {
                //ошибка
                target.classList.remove('disabled');
                sibling.classList.remove('disabled');
            });
        },
    };

})(window);

window.detailSliderInisialized = [];
