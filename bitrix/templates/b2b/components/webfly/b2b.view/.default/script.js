(function () {
    'use strict';

    if (!!window.B2BViewComponent)
        return;

    window.B2BViewComponent = function () {

    };

    window.B2BViewComponent.prototype = {
        init: function () {
            BX.bind(BX('catalog-view-image'), 'click', BX.proxy(this.order, this));
            BX.bind(BX('catalog-view-list'), 'click', BX.proxy(this.order, this));
        },
        order: function () {
            let target = BX.proxy_context,
                activeView = target.value;

            let path = window.location.pathname,
                query = this.buildSortQuery(),
                sortLink;

            if (activeView) {
                sortLink = path + '?view=' + activeView + query;
            } else {
                if (query)
                    sortLink = path + '?' + query.substring(1);
                else
                    sortLink = path;
            }
            window.location.href = sortLink;
        },
        buildSortQuery: function () {
            let query = window.location.search.substring(1);
            let vars = query.split('&'),
                result = '';
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                if (decodeURIComponent(pair[0]) != 'view' && vars[i]) {
                    result += '&' + vars[i];
                }
            }
            return result;
        }
    };

})();
