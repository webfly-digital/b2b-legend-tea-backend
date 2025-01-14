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
        this.obBuyBtn = null;
        this.obBasketActions = null;

        this.offers = [];
        this.treeProps = [];
        this.selectedValues = {};

        this.blockNodes = {};
        this.obProduct = null;
        this.obPrice = null;
        this.obQuantity = null;
        this.obQuantityOffers = [];
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


            if (this.visual.BUY_ID) {
                this.obBuyBtn = BX(this.visual.BUY_ID);
                if (this.obBuyBtn) {
                    this.btnOnBasket = this.obBuyBtn.querySelector('[data-in-basket="Y"]');
                    this.btnOffBasket = this.obBuyBtn.querySelector('[data-in-basket="N"]');
                }
            }


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
                            for (i = 0; i < this.offers.length; i++) {
                                if (BX(this.visual.ID + '_offer_' + this.offers[i].ID)) {
                                    this.obQuantityOffers[this.offers[i].ID] = BX(this.visual.ID + '_offer_' + this.offers[i].ID);
                                }
                            }
                        }
                        break;
                }

                if (this.btnOnBasket) BX.bind(this.btnOnBasket, 'click', BX.proxy(this.obQuantityDownClick, this));
                if (this.btnOffBasket) BX.bind(this.btnOffBasket, 'click', BX.proxy(this.obQuantityUpClick, this));
                this.changeBtnTitle(this.obQuantity.value);


            }
        },

        obQuantityUpClick: function () {
            this.obQuantity.value = 1;
            this.addToBasket();
        },

        obQuantityDownClick: function () {
            this.obQuantity.value = 0;
            this.addToBasket();
        },

        changeBtnTitle: function (quantity = '') {
            if (quantity == '') quantity = this.obQuantity.value;

            if (quantity > 0) {
                this.btnOnBasket.style.display = '';
                this.btnOffBasket.style.display = 'none';
            } else {
                this.btnOnBasket.style.display = 'none';
                this.btnOffBasket.style.display = '';
            }
        },

        startQuantityInterval: function () {
            var target = BX.proxy_context;
            var func = target.id === this.visual.QUANTITY_DOWN_ID
                ? BX.proxy(this.quantityDown, this)
                : BX.proxy(this.quantityUp, this);

            this.quantityDelay = setTimeout(
                BX.delegate(function () {
                    this.quantityTimer = setInterval(func, 150);
                }, this),
                300
            );
        },

        clearQuantityInterval: function () {
            clearTimeout(this.quantityDelay);
            clearInterval(this.quantityTimer);
        },
        quantityUp: function () {
            BX.addClass(this.obQuantityUp, 'disabled');
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
        },
        quantityDown: function () {
            BX.addClass(this.obQuantityDown, 'disabled');
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
        },
        quantityChange: function () {
            var curValue = 0;
            if (this.errorCode === 0) {
                if (this.canBuy) {
                    curValue = Math.round(this.obQuantity.value);
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
            if (this.obBuyBtn) {
                console.log('basketResult');
                console.log(this.obQuantity.value);
                this.changeBtnTitle();
            }
        },
        checkQuantityControls: function () {
            if (!this.obQuantity)
                return;

            var reachedTopLimit = this.checkQuantity && parseFloat(this.obQuantity.value) >= this.maxQuantity,
                reachedBottomLimit = parseFloat(this.obQuantity.value) <= this.minQuantity;

            if (reachedTopLimit) {
                BX.addClass(this.obQuantityUp, 'disabled');
            } else if (BX.hasClass(this.obQuantityUp, 'disabled')) {
                BX.removeClass(this.obQuantityUp, 'disabled');
            }

            if (reachedBottomLimit) {
                BX.addClass(this.obQuantityDown, 'disabled');
            } else if (BX.hasClass(this.obQuantityDown, 'disabled')) {
                BX.removeClass(this.obQuantityDown, 'disabled');
            }

            if (reachedTopLimit && reachedBottomLimit) {
                this.obQuantity.setAttribute('disabled', 'disabled');
            } else {
                this.obQuantity.removeAttribute('disabled');
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

                    if (this.obQuantity)
                        this.obQuantity.removeAttribute('disabled');
                } else {
                    if (this.blockNodes.quantity)
                        BX.addClass(this.blockNodes.quantity, 'disabled');

                    if (this.obQuantity)
                        this.obQuantity.setAttribute('disabled', 'disabled');
                }
                this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
                this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);

                if (BasketToCatalogUpdater.items.hasOwnProperty(newOffer.ID)) {
                    this.obQuantity.value = BasketToCatalogUpdater.items[newOffer.ID];
                } else {
                    this.obQuantity.value = this.minQuantity;
                }
                console.log('quantitySet');
                console.log(this.obQuantity.value);
                this.changeBtnTitle();
                //     this.blockNodes.quantity.dataset.product = newOffer.ID;
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
                    BX.removeClass(this.blockNodes.price, 'disabled');
                } else {
                    BX.adjust(this.obPrice, {html: '-'});
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

                strTreeValue = selected.value;
                arTreeItem = strTreeValue.split('_');

                if (this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1])) {
                    rowItems = BX.findChildren(target, {tagName: 'option'}, false);

                    if (rowItems && 0 < rowItems.length) {
                        for (i = 0; i < rowItems.length; i++) {
                            value = arTreeItem[1];//rowItems[i].getAttribute('data-onevalue');
                            if (value === arTreeItem[1]) {
                                rowItems[i].setAttribute('selected', 'selected');
                            } else {
                                rowItems[i].removeAttribute('selected', 'selected');
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
