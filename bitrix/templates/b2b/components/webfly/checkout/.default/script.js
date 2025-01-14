;(function () {
    'use strict';
    BX.namespace('BX.WebflyCheckoutComponent');
    BX.WebflyCheckoutComponent = {
        init: function (data) {
            this.form = BX('checkout-form');
            this.loader = BX('fullscreenLoader');
            this.basketUrl = data.basketUrl || '';
            this.ajaxUrl = data.ajaxUrl || '';
            this.siteId = data.siteID || '';
            this.signedParamsString = data.signedParamsString || '';
            this.template = data.template || '';
            this.params = data.params || {};
            this.sessidMsg = data.sessidMsg;
            this.choices = {};
            this.data = {
                result: data.result,
                locations: data.locations
            };

            this.BXLocationParams = data.BXLocationParams;

            let that = this;
            this.mixin = {
                methods: {
                    updateProfile: function () {
                        let profileChange = document.getElementById('profile_change');
                        if (profileChange) profileChange.value = 'Y';
                        that.sendRequest();
                        if (profileChange) profileChange.value = 'N';
                    },
                    refresh: function (action, actionData) {
                        that.sendRequest(action, actionData);
                    }
                }
            };

            this.initComponents();
            this.initApp();
        },
        initComponents: function () {
            this.initBasket();
            this.initProducts();
            this.initRegionBlock();
            this.initDeliveryProperties();
            this.initDelivery();
            this.initProperties();
            this.initDeliveryProp();
            this.initAlert();
        },
        initBasket: function () {
            BX.Vue.component('checkout-basket', {
                props: ['basket', 'total'],
                template: '#checkout-basket',
                computed: {
                    length: function () {
                        return Object.keys(this.basket).length;
                    },
                },
            });
        },
        initAlert: function () {
            BX.Vue.component('checkout-alert', {
                props: ['total'],
                template: '#checkout-alert',
                updated() {
                    let bxForm = BX('BX_FORM');
                    let callForm = BX('CALL_FORM');
                    if (callForm) {
                        BX.bind(callForm, 'click', BX.proxy(function () {
                            bxForm.click();
                        }, this));
                    }
                },
            });

        },
        initProducts: function () {
            let mixin = this.mixin,
                that = this;

            BX.Vue.component('checkout-products', {
                mixins: [mixin],
                props: ['products', 'total', 'rows', 'cards'],
                template: '#checkout-products',
                methods: {
                    changeSKU: function (event) {
                        let idSKU = false;
                        let target = event.target;
                        let quantityElem;
                        let json = {};
                        let price = false;
                        let available = false;
                        target = BX.type.isDomNode(target) ? target : BX.proxy_context;

                        if (target.value) idSKU = target.value;
                        if (idSKU) {
                            let select = BX.findParent(target, {'class': 'select'});
                            if (select.hasAttribute('data-json')) {
                                json = JSON.parse(select.getAttribute('data-json'));
                                price = json.PRICE[idSKU];
                                available = json.CATALOG_QUANTITY[idSKU];
                            }

                            let data = this.getRowByProductID(idSKU, false);
                            let sizeElem = BX.findParent(target, {'class': 'size'});
                            if (sizeElem) {
                                quantityElem = sizeElem.querySelector('[name="QUANTITY"]');
                                if (typeof available === 'string') {
                                    if (available > 0) {
                                        if (sizeElem.classList.contains('out-of-stock')) sizeElem.classList.remove('out-of-stock');
                                        if (!sizeElem.classList.contains('in-stock')) sizeElem.classList.add('in-stock');
                                    } else {
                                        if (sizeElem.classList.contains('in-stock')) sizeElem.classList.remove('in-stock');
                                        if (!sizeElem.classList.contains('out-of-stock')) sizeElem.classList.add('out-of-stock');
                                    }
                                }
                                if (typeof price === 'string') {
                                    let elemPrice = sizeElem.querySelector('[data-price]')
                                    elemPrice.innerText = price;
                                }
                            }
                            if (data.itemData && data.itemNode) {
                                let quantityField = data.itemNode.querySelector('[name="QUANTITY"]');
                                if (quantityElem) quantityElem.value = Math.round(quantityField.value);
                            } else {
                                if (quantityElem) quantityElem.value = 0;
                            }
                        }
                    },
                    showPopupStock: function (event) {
                        if (window.innerWidth <= 1200) {
                            let target = event.target;
                            target = BX.type.isDomNode(target) ? target : BX.proxy_context;

                            let labels = target.closest('.labels');
                            if (labels) {
                                var dayElem = labels.querySelector('span.day');
                                var day = dayElem.innerText;
                            }

                            let obPopupStock = BX('popupStock');
                            if (obPopupStock) {
                                let text = obPopupStock.querySelector('span span.day');
                                if (day) text.innerText = day;

                                let closer = obPopupStock.querySelector(".button-full");
                                let shade = document.querySelector('.shade');
                                BX.show(obPopupStock);
                                shade.classList.remove('disable');
                                BX.bind(closer, 'click', function (event) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                    BX.hide(obPopupStock);
                                    shade.classList.add('disable');
                                });
                                shade.addEventListener('click', () => {
                                    BX.hide(obPopupStock);
                                    shade.classList.add('disable');
                                });
                            }
                        }
                    },
                    showDetailInfo: function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        let target = event.target;
                        target = BX.type.isDomNode(target) ? target : BX.proxy_context;

                        let content = document.querySelector(".product-slide-info");
                        let shade = document.querySelector(".shade");
                        // let detailContainer = document.querySelector('.product-slide-content');
                        let row = target.closest('.product-table-row');

                        if (row) {
                            let parentId = row.getAttribute('data-parent-id');
                            let obDetailData = document.querySelector('[data-parent-id-card="' + parentId + '"]')
                            if (obDetailData) {
                                obDetailData.classList.add('show-card')
                                initDetailCard();
                                initProductDetailSlider();
                                content.classList.add("show");
                                shade.classList.remove("disable");
                                BX.bind(obDetailData, 'click', function (e) {
                                    if (!e.composedPath().includes(obDetailData.querySelector('.body'))) {
                                        e.stopPropagation()
                                        content.classList.remove("show");
                                        obDetailData.classList.remove('show-card')
                                        shade.classList.add("disable");
                                    }
                                })
                            }

                        }
                    },
                    handlerQuantity: function (content) {
                        // let obQuantityDown = content.querySelectorAll('[data-quantity-down]');
                        // let obQuantity = content.querySelectorAll('[data-quantity]');
                        // let obQuantityUp = content.querySelectorAll('[data-quantity-up]');
                        // let obPrice = content.querySelectorAll('[data-price]');
                        // let obOffers = content.querySelectorAll('[data-offers]');
                        // let obQuantityDetail = {};
                        //
                        // if (typeof this.JCCatalogItem == 'object') {
                        //     this.JCCatalogItem.forEach((item, key) => {
                        //         obQuantityDetail = {
                        //             obQuantityUp: obQuantityUp[key],
                        //             obQuantity: obQuantity[key],
                        //             obQuantityDown: obQuantityDown[key],
                        //             obPrice: obPrice[key] ? obPrice[key] : null,
                        //         };
                        //         if (obOffers[key]) {
                        //             let offer = obOffers[key].querySelector('.select');
                        //             obQuantityDetail.obOffer = offer;
                        //         }
                        //         this.JCCatalogItem[key].quntityDetail = obQuantityDetail;
                        //
                        //         if (!this.handlerDetailInit[key].handlerOffers && obQuantityDetail.obOffer) {
                        //             this.handlerDetailInit[key].handlerOffers = true;
                        //
                        //             let treeItems = BX.findChildren(obQuantityDetail.obOffer, {className: 'sku-change'}, true);
                        //             if (treeItems) {
                        //                 treeItems.forEach(item => {
                        //                     BX.bind(item, 'change', BX.delegate(function () {
                        //                         this.JCCatalogItem[key].selectOfferProp()
                        //                     }, this));
                        //                 });
                        //                 this.JCCatalogItem[key].setCurrent();
                        //             }
                        //         }
                        //
                        //
                        //         if (!this.handlerDetailInit[key].handlercheckQuantityControlsDetail) {
                        //             this.handlerDetailInit[key].handlercheckQuantityControlsDetail = true;
                        //             this.JCCatalogItem[key].checkQuantityControls();
                        //         }
                        //
                        //         if (this.JCCatalogItem[key].quntityDetail.obQuantityUp && !this.handlerDetailInit[key].handlerQuantityUpDetail) {
                        //             this.handlerDetailInit[key].handlerQuantityUpDetail = true
                        //
                        //             BX.bind(this.JCCatalogItem[key].quntityDetail.obQuantityUp, 'click', BX.delegate(function () {
                        //                 this.JCCatalogItem[key].quantityUp()
                        //             }, this));
                        //         }
                        //         if (this.JCCatalogItem[key].quntityDetail.obQuantityDown && !this.handlerDetailInit[key].handlerQuantityDownDetail) {
                        //             this.handlerDetailInit[key].handlerQuantityDownDetail = true
                        //
                        //             BX.bind(this.JCCatalogItem[key].quntityDetail.obQuantityDown, 'click', BX.delegate(function () {
                        //                 this.JCCatalogItem[key].quantityDown()
                        //             }, this));
                        //         }
                        //
                        //         if (this.JCCatalogItem[key].quntityDetail.obQuantity) {
                        //             this.JCCatalogItem[key].quntityDetail.obQuantity.value = this.JCCatalogItem[key].obQuantity.value;
                        //             if (!this.handlerDetailInit[key].handlerQuantityDetail) {
                        //                 this.handlerDetailInit[key].handlerQuantityDetail = true
                        //
                        //                 BX.bind(this.JCCatalogItem[key].quntityDetail.obQuantity, 'change', BX.delegate(function () {
                        //                     this.JCCatalogItem[key].quantityChange()
                        //                 }, this));
                        //             }
                        //         }
                        //     })
                        // }
                    },
                    quantityPlus: function (e) {
                        let target = e.target;
                        target = BX.type.isDomNode(target) ? target : BX.proxy_context;
                        let data, select, option, skuId;

                        let type = target.hasAttribute('data-card');
                        if (type) {
                            let sizeElem = BX.findParent(target, {'class': 'size'});
                            if (sizeElem) {
                                select = sizeElem.querySelector('.product-table-cell select');
                                if (select) {
                                    skuId = select.value; //если нативный селект
                                    if (skuId == null || skuId == '') { // если чоисес
                                        option = select.querySelector('option');
                                        if (option) skuId = option.value;
                                    }
                                } else {
                                    skuId = target.getAttribute('data-card');
                                }
                                if (skuId) data = this.getRowByProductID(skuId, 1, sizeElem);
                            }
                        } else {
                            data = this.getItemDataByTarget(target);
                        }

                        if (data && data.itemData && data.itemNode) {
                            let quantityField = data.itemNode.querySelector('[name=QUANTITY]'),
                                currentQuantity = Math.round(quantityField.value),
                                measureRatio = 1,
                                quantity = parseFloat((currentQuantity + measureRatio).toFixed(5));
                            quantity = this.getCorrectQuantity(data.itemData, quantity);
                            this.setQuantityViaAjax(data.itemData, quantityField, quantity);
                        }
                    },
                    quantityMinus: function (e) {
                        let target = e.target;
                        target = BX.type.isDomNode(target) ? target : BX.proxy_context;
                        let data, select, option, skuId;

                        let type = target.hasAttribute('data-card');
                        if (type) {
                            let sizeElem = BX.findParent(target, {'class': 'size'});
                            if (sizeElem) {
                                select = sizeElem.querySelector('.product-table-cell select');
                                if (select) {
                                    skuId = select.value; //если нативный селект
                                    if (skuId == null || skuId == '') { // если чоисес
                                        option = select.querySelector('option');
                                        if (option) skuId = option.value;
                                    }
                                } else skuId = target.getAttribute('data-card');
                                if (skuId) data = this.getRowByProductID(skuId);
                            }
                        } else {
                            data = this.getItemDataByTarget(target);
                        }

                        if (data && data.itemData && data.itemNode) {
                            let quantityField = data.itemNode.querySelector('[name=QUANTITY]'),
                                currentQuantity = Math.round(quantityField.value),
                                measureRatio = 1,
                                quantity = parseFloat((currentQuantity - measureRatio).toFixed(5));

                            quantity = this.getCorrectQuantity(data.itemData, quantity);
                            this.setQuantityViaAjax(data.itemData, quantityField, quantity);
                        }
                    },
                    quantityChange: function (e) {
                        let target = e.target;
                        target = BX.type.isDomNode(target) ? target : BX.proxy_context;
                        let data, select, skuId;

                        let type = target.hasAttribute('data-card');
                        if (type) {
                            let sizeElem = BX.findParent(target, {'class': 'size'});
                            if (sizeElem) {
                                select = sizeElem.querySelector('.product-table-cell select');
                                if (select) skuId = select.value;
                                else skuId = target.getAttribute('data-card');
                                data = this.getRowByProductID(skuId, target.value);
                                if (data.itemNode) data.itemNode.querySelector('[name=QUANTITY]').value = target.value;
                            }
                        } else {
                            data = this.getItemDataByTarget(target);
                        }

                        if (data.itemData && data.itemNode) {
                            let quantityField = data.itemNode.querySelector('[name=QUANTITY]'),
                                quantity = this.getCorrectQuantity(data.itemData, quantityField.value);
                            this.setQuantityViaAjax(data.itemData, quantityField, quantity);
                        }
                    },
                    getItemDataByTarget: function (target) {
                        let data = {itemNode: '', itemData: {}}, id;
                        data.itemNode = BX.findParent(target, {'class': 'product-table-row'});
                        if (data.itemNode) {
                            id = data.itemNode.getAttribute('data-id');
                            data.itemData = this.rows[id];
                        }
                        return data;
                    },
                    getRowByProductID: function (idSKU, currentQuantity = 0, target = false) {
                        let data = {itemNode: '', itemData: {}}, id;
                        data.itemNode = document.querySelector('.product-table-row[data-product-id="' + idSKU + '"]');
                        if (data.itemNode) {
                            id = data.itemNode.getAttribute('data-id');
                            data.itemData = this.rows[id];
                        } else {
                            if (currentQuantity > 0) {
                                this.newProductAddToBasket(idSKU, currentQuantity, target)
                            }
                        }
                        return data;
                    },
                    newProductAddToBasket: function (idSKU, currentQuantity, target) {
                        let dataNewProduct = {};
                        dataNewProduct.ID = idSKU;
                        dataNewProduct.QUANTITY = currentQuantity;
                        dataNewProduct.action = 'add';
                        dataNewProduct.sessid = BX.bitrix_sessid();
                        BX.ajax({
                            method: 'POST',
                            dataType: 'json',
                            url: BX.WebflyCheckoutComponent.basketUrl,
                            data: dataNewProduct,
                            onsuccess: BX.delegate(function (result) {
                                this.refresh('', '')
                            }, this)
                        });
                    },
                    getCorrectQuantity: function (itemData, quantity) {
                        let availableQuantity = 0;
                        quantity = parseInt(quantity, 10) || 0;
                        if (quantity < 0) {
                            quantity = 0;
                        }
                        availableQuantity = parseInt(itemData.data.AVAILABLE_QUANTITY);
                        if (availableQuantity > 0 && quantity > availableQuantity) {
                            quantity = availableQuantity;
                        }
                        quantity = parseInt(quantity, 10);
                        return quantity;
                    },
                    setQuantityViaAjax: function (data, quantityField, quantity) {
                        quantityField = BX.type.isDomNode(quantityField);
                        if (quantityField) {
                            quantity = parseFloat(quantity);
                            if (parseFloat(data.QUANTITY) !== quantity) {
                                this.refresh('changeQuantity', {
                                    productId: data.data.PRODUCT_ID,
                                    quantity: quantity
                                });
                            }
                        }
                    },
                    remove: function (e) {
                        let target = e.target;
                        target = BX.type.isDomNode(target) ? target : BX.proxy_context;
                        let data = this.getItemDataByTarget(target);

                        if (data.itemData) {
                            let quantityField = data.itemNode.querySelector('[name=QUANTITY]'),
                                quantity = 0;

                            this.setQuantityViaAjax(data.itemData, quantityField, quantity);
                        }
                    },
                },
                updated() {
                    // console.log("updated");
                    // console.log(this);
                    // console.log(that.choices)
                    // let genericExamples = that.choices;
                    // for (let i = 0; i < genericExamples.length; ++i) {
                    //     let element = genericExamples[i];
                    //     element.destroy()
                    //    // console.log(JSON.stringify(element.value));
                    //   //  console.log(JSON.stringify(element));
                    // }
                    // console.log(that.choices)
                    // for (let index in this.choices) {
                    //     let choicesItem = this.choices[index];
                    //     if (choicesItem.containerOuter) {
                    //         let select = BX.findParent(choicesItem.containerOuter.element, {'class': 'select'});
                    //         if (select && select.hasAttribute('data-selected')) {
                    //             let selectedID = select.getAttribute('data-selected');
                    //             let optionSelect = choicesItem.containerInner.element.querySelector('option')
                    //             if (optionSelect.value && optionSelect.value != selectedID) {
                    //                 choicesItem.setChoiceByValue(select.getAttribute('data-selected'))
                    //             }
                    //         }
                    //     }
                    // }
                },
                // mounted() {
                //     this.choices = {};
                //     document.querySelectorAll('.product-slide-sizes .size select.choices').forEach((select, index) => {
                //         let selectElem = new Choices(select, {
                //             searchEnabled: false,
                //             itemSelectText: '',
                //             shouldSort: false
                //         });
                //         this.choices[index] = selectElem;
                //     })
                // },
            });
        },
        initRegionBlock: function () {
            let mixin = this.mixin,
                that = this;

            that.locationsInitialized = false;
            that.locations = {};
            that.cleanLocations = {};
            that.locationsTemplate = '';

            that.deliveryLocationInfo = {};

            BX.Vue.component('checkout-region-block', {
                mixins: [mixin],
                props: ['regiondata', 'properties', 'locations'],
                template: '#checkout-region-block',
                computed: {
                    length: function () {
                        return Object.keys(this.regiondata.persontype).length;
                    },
                    oldPersonType: function () {
                        if (this.length > 1) {
                            let persontype = this.regiondata.persontype;
                            for (let i in persontype) {
                                if (persontype.hasOwnProperty(i)) {
                                    if (persontype[i].CHECKED == 'Y')
                                        return persontype[i].ID;
                                }
                            }
                        }
                        return false;
                    },
                    checkedPersonType: function () {
                        if (this.length > 1) {
                            let persontype = this.regiondata.persontype;
                            for (let i in persontype) {
                                if (persontype.hasOwnProperty(i)) {
                                    if (persontype[i].CHECKED == 'Y')
                                        return persontype[i].ID;
                                }
                            }
                        }
                        return false;
                    },
                    showProfileSelect: function () {
                        if (this.regiondata.profile) {
                            return this.regiondata.showProfileSelect == 'Y' && Object.keys(this.regiondata.profile).length > 0;
                        } else {
                            return false;
                        }
                    },
                },
                methods: {
                    prepareLocations: function (locations) {
                        that.locations = {};
                        that.cleanLocations = {};

                        let locationRow = that.form.querySelector('.location-row');
                        if (locationRow) locationRow.innerHTML = '';

                        let temporaryLocations,
                            i, k, output;

                        if (BX.util.object_keys(locations).length) {
                            for (i in locations) {
                                if (!locations.hasOwnProperty(i))
                                    continue;

                                that.locationsTemplate = locations[i].template || '';
                                temporaryLocations = [];
                                output = locations[i].output;

                                if (output.clean) {
                                    that.cleanLocations[i] = BX.processHTML(output.clean, false);
                                    delete output.clean;
                                }

                                for (k in output) {
                                    if (output.hasOwnProperty(k)) {
                                        temporaryLocations.push({
                                            output: BX.processHTML(output[k], false),
                                            showAlt: locations[i].showAlt,
                                            lastValue: locations[i].lastValue,
                                            coordinates: locations[i].coordinates || false
                                        });
                                    }
                                }

                                that.locations[i] = temporaryLocations;
                            }
                        }
                    },
                    initPropsListForLocation: function () {
                        if (BX.saleOrderAjax && this.properties) {
                            var i, k, curProp, attrObj;

                            BX.saleOrderAjax.cleanUp();

                            for (i = 0; i < this.properties.length; i++) {
                                curProp = this.properties[i];

                                if (curProp.TYPE == 'LOCATION' && curProp.MULTIPLE == 'Y' && curProp.IS_LOCATION != 'Y') {
                                    for (k = 0; k < this.locations[curProp.ID].length; k++) {
                                        BX.saleOrderAjax.addPropertyDesc({
                                            id: curProp.ID + '_' + k,
                                            attributes: {
                                                id: curProp.ID + '_' + k,
                                                type: curProp.TYPE,
                                                valueSource: curProp.SOURCE == 'DEFAULT' ? 'default' : 'form'
                                            }
                                        });
                                    }
                                } else {
                                    attrObj = {
                                        id: curProp.ID,
                                        type: curProp.TYPE,
                                        valueSource: curProp.SOURCE == 'DEFAULT' ? 'default' : 'form'
                                    };

                                    if (!that.deliveryLocationInfo.city && parseInt(curProp.INPUT_FIELD_LOCATION) > 0) {
                                        attrObj.altLocationPropId = parseInt(curProp.INPUT_FIELD_LOCATION);
                                        that.deliveryLocationInfo.city = curProp.INPUT_FIELD_LOCATION;
                                    }

                                    if (!that.deliveryLocationInfo.loc && curProp.IS_LOCATION == 'Y')
                                        that.deliveryLocationInfo.loc = curProp.ID;

                                    if (!that.deliveryLocationInfo.zip && curProp.IS_ZIP == 'Y') {
                                        attrObj.isZip = true;
                                        that.deliveryLocationInfo.zip = curProp.ID;
                                    }

                                    BX.saleOrderAjax.addPropertyDesc({
                                        id: curProp.ID,
                                        attributes: attrObj
                                    });
                                }
                            }
                        }
                    },
                    getDeliveryLocationInput: function () {
                        let locationProperty = this.regiondata.properties.location, locationId, location, k,
                            currentLocation, label, inputDiv, hintDiv, hiddenInput,
                            locationRow = this.$el.querySelector('.location-row');

                        locationId = locationProperty.ID;
                        location = that.locations[locationId];

                        if (location && location[0] && location[0].output) {
                            label = document.createElement('label');
                            label.classList.add('custom-input');
                            label.setAttribute('for', 'soa-property-' + locationId);

                            inputDiv = document.createElement('div');
                            inputDiv.classList.add('input');

                            hintDiv = document.createElement('div');
                            hintDiv.classList.add('input-title');
                            hintDiv.innerText = locationProperty.DESCRIPTION;

                            currentLocation = location[0].output;

                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'RECENT_DELIVERY_VALUE';
                            hiddenInput.value = location[0].lastValue;

                            label.append(hintDiv);
                            locationRow.append(label);
                            label.append(inputDiv);
                            inputDiv.insertAdjacentHTML('beforeend', currentLocation.HTML);

                            locationRow.append(hiddenInput);

                            for (k in currentLocation.SCRIPT) {
                                if (currentLocation.SCRIPT.hasOwnProperty(k)) {
                                    BX.evalGlobal(currentLocation.SCRIPT[k].JS);
                                }
                            }
                        }
                    },
                },
                created() {
                    this.prepareLocations(that.data.locations);
                    this.initPropsListForLocation();
                },
                beforeUpdate() {
                    this.prepareLocations(this.locations);
                    this.initPropsListForLocation();
                    that.locationsInitialized = false;
                },
                mounted() {
                    this.getDeliveryLocationInput();
                    BX.saleOrderAjax.init(that.BXLocationParams);
                },
                updated() {
                    this.getDeliveryLocationInput();
                    BX.saleOrderAjax && BX.saleOrderAjax.initDeferredControl();
                }
            });
        },
        initDeliveryProp: function () {
            let mixin = this.mixin;
            BX.Vue.component('checkout-delivery-prop', {
                mixins: [mixin],
                props: ['grouped_properties'],
                template: '#checkout-delivery-prop',
                computed: {
                    length: function () {
                        return Object.keys(this.grouped_properties).length;
                    }
                }
            });
        },
        initDeliveryProperties: function () {
            let mixin = this.mixin;
            BX.Vue.component('checkout-delivery-properties', {
                mixins: [mixin],
                props: ['grouped_properties'],
                template: '#checkout-delivery-properties',
                computed: {
                    length: function () {
                        return Object.keys(this.grouped_properties).length;
                    }
                }
            });
        },
        initDelivery: function () {
            let that = this, mixin = that.mixin;
            BX.Vue.component('checkout-delivery', {
                mixins: [mixin],
                props: ['delivery', 'regiondata'],
                template: '#checkout-delivery',
                computed: {
                    length: function () {
                        return Object.keys(this.delivery).length;
                    },
                },
                methods: {
                    openYaCart: function (e) {
                        let x = e.target;
                        x = BX.type.isDomNode(x) ? x : BX.proxy_context;
                        let targetId = x.getAttribute("data-target");
                        let target = document.querySelector(targetId);
                        let shade = document.querySelector('.shade');
                        target.classList.remove('disable');
                        shade.classList.remove('disable');

                        shade.addEventListener('click', () => {
                            target.classList.add('disable');
                            shade.classList.add('disable');
                        })
                    },
                },
            });
        },
        initProperties: function () {
            let mixin = this.mixin, that = this;
            BX.Vue.component('checkout-properties', {
                mixins: [mixin],
                props: ['grouped_properties', 'user_data', 'contact_person'],
                template: '#checkout-properties',
                computed: {
                    length: function () {
                        return Object.keys(this.grouped_properties).length;
                    },
                    user_data_length: function () {
                        return Object.keys(this.user_data).length;
                    }
                },
                methods: {
                    setDefaultUserData: function (e) {
                        let x = e.target;
                        x = BX.type.isDomNode(x) ? x : BX.proxy_context;

                        let defaultData = that.form.querySelectorAll('.default-user_data');
                        if (defaultData) {
                            defaultData.forEach(input => {
                                let userInput = that.form.querySelector('[data-code=' + input.getAttribute('name') + ']');
                                if (userInput) {
                                    if (x.checked)
                                        userInput.value = input.value;
                                    else
                                        userInput.value = '';
                                }
                            });
                        }
                    },
                },
            });
        },
        initApp: function () {
            let that = this,
                result = that.data.result,
                locations = that.data.locations;

            this.checkoutApp = BX.Vue.createApp({
                el: '#checkout-app',
                data() {
                    return {
                        total: result.TOTAL,
                        products: result.PRODUCTS,
                        cards: result.CARDS,
                        rows: result.GRID.ROWS,
                        properties: result.ORDER_PROP.properties,
                        regiondata: result.REGION_DATA,
                        locations: locations,
                        delivery: result.DELIVERY,
                        grouped_properties: result.ORDER_PROP.GROUPED_PROPERTIES,
                        user_data: result.USER_DATA,
                        basket: result.BASKET,
                        contact_person: result.CONTACT_PERSON
                    }
                },
                template: '#checkout',
                methods: {
                    refresh(result) {
                        console.log(result);
                        this.total = result.order.TOTAL;
                        this.products = result.order.PRODUCTS;
                        this.cards = result.order.CARDS;
                        this.rows = result.order.GRID.ROWS;
                        this.properties = result.order.ORDER_PROP.properties;
                        this.regiondata = result.order.REGION_DATA;
                        this.locations = result.locations;
                        this.delivery = result.order.DELIVERY;
                        this.grouped_properties = result.order.ORDER_PROP.GROUPED_PROPERTIES;
                        this.user_data = result.order.USER_DATA;
                        this.basket = result.order.BASKET;
                        this.contact_person = result.order.CONTACT_PERSON;
                    },
                    closePopup(e) {
                        let x = e.target;
                        x = BX.type.isDomNode(x) ? x : BX.proxy_context;
                        let target = x.closest('.wf-popup');
                        let shade = document.querySelector('.shade');
                        target.classList.add('disable');
                        shade.classList.add('disable');
                    }
                },
                mounted() {
                    that.onAfterUpdate();
                    /**
                     * Назначение валидатора
                     * @type {Element}
                     */
                    const checkoutForm = that.form;
                    if (checkoutForm) {
                        initForm(checkoutForm);
                    }
                    if (result.hasOwnProperty('REFRESH_FIELD_ID')) {
                        BX.fireEvent(BX(result.REFRESH_FIELD_ID), 'click');
                    }
                },
                updated() {
                    that.onAfterUpdate();
                    /**
                     * Назначение валидатора
                     * @type {Element}
                     */
                    const checkoutForm = that.form;
                    if (checkoutForm) {
                        reInitForm(checkoutForm);
                    }
                },
            });
        },
        onAfterUpdate: function () {
            this.initChoices();
            this.hideLoader();
        },
        initChoices: function () {
            let genericExamples = this.form.querySelectorAll('.product-slide-sizes .size select.choices');
            for (let i = 0; i < genericExamples.length; ++i) {
                let element = genericExamples[i];
                this.choices[i] = new Choices(element, {
                    searchEnabled: false,
                    itemSelectText: '',
                    shouldSort: false
                });

                if (this.choices[i].passedElement.element && this.choices[i].passedElement.element.value.length == 0) { //почему то у чоисес выбирается не актуальный помол после удаления позиции, поэтому принудительно ставим колво 0
                    let sizeElem = BX.findParent(this.choices[i].passedElement.element, {'class': 'size'});
                    if (sizeElem) {
                        let inputQuantity = sizeElem.querySelector('[name="QUANTITY"]');
                        if (inputQuantity) inputQuantity.value = 0
                    }
                }
            }
        },
        sendRequest: function (action, actionData) {

            this.showLoader();
            action = BX.type.isNotEmptyString(action) ? action : 'refreshOrderAjax';

            let eventArgs = {
                action: action,
                actionData: actionData,
                cancel: false
            };

            if (eventArgs.action === 'saveOrderAjax') {
                let form = this.form;
                if (form) form.querySelector('input[type=hidden][name=sessid]').value = BX.bitrix_sessid();

                BX.ajax.submitAjax(
                    form,
                    {
                        url: this.ajaxUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            via_ajax: 'Y',
                            action: 'saveOrderAjax',
                            sessid: BX.bitrix_sessid(),
                            SITE_ID: this.siteId,
                            template: this.template,
                            signedParamsString: this.signedParamsString
                        },
                        onsuccess: BX.proxy(this.saveOrderWithJson, this),
                        onfailure: BX.proxy(this.handleNotRedirected, this)
                    }
                );
            } else {
                BX.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: this.ajaxUrl,
                    data: this.getData(action, actionData),
                    onsuccess: BX.delegate(function (result) {
                        if (result.redirect && result.redirect.length)
                            document.location.href = result.redirect;
                        this.refreshOrder(result);
                    }, this),
                    onfailure: BX.delegate(function () {
                        this.hideLoader();
                    }, this)
                });
            }
        },
        saveOrderWithJson: function (result) {
            if (result && result.order) {
                result = result.order;

                if (result.REDIRECT_URL) {
                    location.href = result.REDIRECT_URL;
                } else if (result.ERROR) {
                    this.showErrors(result.ERROR);
                } else {
                    this.showErrors({SESSID: this.sessidMsg});
                }
                this.hideLoader();
            }
        },
        showErrors: function (errors) {
            let k, msg, msgHtml = '', msgByBlock = '';

            if (!errors || BX.util.object_keys(errors).length < 1)
                return;

            for (k in errors) {
                if (!errors.hasOwnProperty(k))
                    continue;
                msg = errors[k];
                msgByBlock = this.appendError(msg);

                if (msgByBlock != '') {
                    msgHtml += msgByBlock + '<br>';
                }
            }
            if (msgHtml != '') {
                this.popupShow(msgHtml);
            }
        },
        popupShow: function (msgHtml) {
            if (this.popup)
                this.popup.destroy();

            if (!msgHtml) return;

            this.popup = new BX.PopupWindow('bx-soa-popup', null, {
                lightShadow: true,
                offsetTop: 0,
                offsetLeft: 0,
                closeIcon: {top: '3px', right: '10px'},
                autoHide: true,
                bindOptions: {position: "bottom"},
                closeByEsc: true,
                zIndex: 1000,
                events: {
                    onPopupClose: function () {
                        this.destroy();
                    }
                },
                overlay: {
                    backgroundColor: 'grey', opacity: '60'
                },
                content: BX.create('DIV', {
                    props: {id: 'checkout-error'},
                    html: msgHtml
                })
            });
            this.popup.show();
        },
        appendError: function (msg) {
            if (BX.type.isArray(msg))
                msg = msg.join('<br>');
            let msgHtml = '';
            if (msg.length) {
                msgHtml = msg;
            }
            return msgHtml;
        },
        handleNotRedirected: function () {
            this.showErrors({SESSID: this.sessidMsg});
            this.hideLoader();
        },
        refreshOrder: function (result) {
            if (result.error) {
                this.popupShow(result.error);
                this.hideLoader();
            } else {
                this.checkoutApp.refresh(result);
            }
        },
        getData: function (action, actionData) {
            let data = {
                order: this.getAllFormData(),
                sessid: BX.bitrix_sessid(),
                via_ajax: 'Y',
                SITE_ID: this.siteId,
                template: this.template,
                signedParamsString: this.signedParamsString
            };

            data[this.params.ACTION_VARIABLE] = action;

            if (action === 'changeQuantity')
                data.change_quantity_item = actionData;
            return data;
        },
        getAllFormData: function () {
            let form = this.form,
                prepared = BX.ajax.prepareForm(form),
                i;

            for (i in prepared.data) {
                if (prepared.data.hasOwnProperty(i) && i == '') {
                    delete prepared.data[i];
                }
            }

            return !!prepared && prepared.data ? prepared.data : {};
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
