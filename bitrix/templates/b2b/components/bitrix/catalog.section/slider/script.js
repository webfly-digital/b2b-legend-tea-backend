(function () {
    'use strict';

    if (!!window.JCCatalogSectionComponent)
        return;

    window.JCCatalogSectionComponent = function (params) {
        this.loader = document.querySelector('#fullscreenLoader');
        this.formPosting = false;
        this.siteId = params.siteId || '';
        this.ajaxId = params.ajaxId || '';
        this.template = params.template || '';
        this.componentPath = params.componentPath || '';
        this.parameters = params.parameters || '';

        if (params.navParams) {
            this.navParams = {
                NavNum: params.navParams.NavNum || 1,
                NavPageNomer: parseInt(params.navParams.NavPageNomer) || 1,
                NavPageCount: parseInt(params.navParams.NavPageCount) || 1
            };
        }

        this.sectionAjaxPath = params.sectionAjaxPath || '';

        this.container = document.querySelector('[data-entity="' + params.container + '"]');

        /**
         * kjad item for first sections
         * @type {Element}
         */
        let openSpoiler = document.querySelector('.spoiler.open-spoiler');
        if (openSpoiler && this.navParams.NavPageCount > 1) {
            this.getSectionItems(openSpoiler, true);
        }

        let ajaxTogglers = document.querySelectorAll('.spoiler.no-init');

        if (ajaxTogglers) {
            ajaxTogglers.forEach(toggler => {
                BX.bind(toggler, 'click', BX.proxy(this.getSectionItems, this));
                //BX.bind(toggler, 'click', BX.proxy(function(){this.getSectionItems(toggler, true)}, this));
            });
        }
    };

    window.JCCatalogSectionComponent.prototype =
        {
            updateFavorite: function () {//вызывеатся из component_epilog
                let data = {jsItems: this.jsItems};
                this.runComponentAction('getFavorite', data);
            },
            runComponentAction: function (action, data) {
                BX.ajax.runComponentAction('webfly:b2b.favorites', action, {
                    mode: 'class',
                    data: data,
                }).then(function (response) {
                    if (response.data) {
                        response.data.forEach(fav => {
                            let favItem = BX(fav + '_favorite'),
                                favItemDetail = BX(fav + '_favorite_detail');
                            if (favItem) {
                                favItem.classList.add('active');
                            }
                            if (favItemDetail) {
                                favItemDetail.classList.add('active');
                            }
                        });
                    }
                }, function () {
                    //ошибка

                });
            },
            getSectionItems: function (target, hideloader) {

                target = BX.type.isDomNode(target) ? target : BX.proxy_context;
                let sid = target.dataset.sid,
                    scode = target.dataset.code,
                    page = target.dataset.page;

                let data = {};
                data['action'] = 'showMore';
                data['PAGEN_' + this.navParams.NavNum] = page;
                data['SECTION_ID'] = sid;
                data['SECTION_CODE'] = scode;

                //if (!this.formPosting) {
                this.formPosting = true;
                this.sendRequest(data, target, hideloader);
                // }
            },
            sendRequest: function (data, target, hideloader) {
                let _this = this;
                if (!hideloader) this.showLoader();
                var defaultData = {
                    siteId: this.siteId,
                    template: this.template,
                    parameters: this.parameters
                };

                if (this.ajaxId) {
                    defaultData.AJAX_ID = this.ajaxId;
                }

                BX.ajax({
                    url: this.sectionAjaxPath + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
                    method: 'POST',
                    dataType: 'json',
                    timeout: 60,
                    data: BX.merge(defaultData, data),
                    onsuccess: BX.delegate(function (result) {
                        if (!result || !result.JS)
                            return;

                        BX.ajax.processScripts(
                            BX.processHTML(result.JS).SCRIPT,
                            false,
                            BX.delegate(function () {
                                this.showAction(result, data);
                                if (result.pageCount) {
                                    if (result.pageCount > parseInt(target.dataset.page)) {
                                        target.dataset.page = parseInt(target.dataset.page) + 1;
                                        this.getSectionItems(target, true);
                                    }
                                }
                            }, this)
                        );
                        if (!hideloader) _this.hideLoader();
                    }, this),
                    onfailure: BX.delegate(function () {
                        if (!hideloader) _this.hideLoader();
                    }, this),
                });
            },
            showAction: function (result, data) {
                if (!data)
                    return;

                switch (data.action) {
                    case 'showMore':
                        this.processShowMoreAction(result, data);
                        break;
                }
            },
            processShowMoreAction: function (result, data) {
                this.formPosting = false;
                if (result) {
                    this.processItems(result.items, data);
                    this.processEpilogue(result.epilogue);
                }
            },
            processItems: function (itemsHtml, data) {
                if (!itemsHtml)
                    return;


                var processed = BX.processHTML(itemsHtml, false),
                    temporaryNode = BX.create('DIV');

                var items, k, spoiler, origRow;

                temporaryNode.innerHTML = processed.HTML;
                items = temporaryNode.querySelectorAll('[data-entity="item"]');
                spoiler = this.container.querySelector('#spoiler-' + data.SECTION_ID);

                if (items.length) {

                    origRow = spoiler.querySelector('[data-entity="items-row"]');
                    for (k in items) {
                        if (items.hasOwnProperty(k)) {
                            items[k].style.opacity = 0;
                            window.catalogMobileSpoiler(items[k]);
                            if (origRow) {
                                origRow.appendChild(items[k]);
                            }
                        }
                    }

                    new BX.easing({
                        duration: 2000,
                        start: {opacity: 0},
                        finish: {opacity: 100},
                        transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
                        step: function (state) {
                            for (var k in items) {
                                if (items.hasOwnProperty(k)) {
                                    items[k].style.opacity = state.opacity / 100;
                                }
                            }
                        },
                        complete: function () {
                            for (var k in items) {
                                if (items.hasOwnProperty(k)) {
                                    items[k].removeAttribute('style');
                                }
                            }
                        }
                    }).animate();
                }

                if (spoiler.classList.contains('no-init')) {
                    spoiler.classList.remove('no-init');
                    window.initSpoiler(spoiler);
                    BX.unbind(spoiler, 'click', BX.proxy(this.getSectionItems, this));
                    spoiler.querySelector('.toggler').click();
                }

                BX.ajax.processScripts(processed.SCRIPT);
                BX.onCustomEvent('OnShowCatalogItems');
            },
            processEpilogue: function (epilogueHtml) {
                if (!epilogueHtml)
                    return;

                var processed = BX.processHTML(epilogueHtml, false);
                BX.ajax.processScripts(processed.SCRIPT);
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
