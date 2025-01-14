(function () {
    'use strict';

    if (!!window.B2BOrderComponent)
        return;

    window.B2BOrderComponent = function () {

    };

    window.B2BOrderComponent.prototype = {
        init: function () {
            BX.bind(BX('catalog-sort'), 'change', BX.proxy(this.order, this));
        },
        order: function () {
            let target = BX.proxy_context,
                activeSort = target.options[target.selectedIndex],
                sort, order;
            if (activeSort) {
                sort = activeSort.value;
                order = activeSort.dataset.order;
            }
            let path = window.location.pathname,
                query = this.buildSortQuery(),
                sortLink;

            if (sort && order) {
                sortLink = path + '?sort=' +sort +'&order='+order + query;
            }else{
                if (query)
                    sortLink = path+'?'+ query.substring(1);
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
                if (decodeURIComponent(pair[0]) != 'sort' && decodeURIComponent(pair[0]) != 'order' && vars[i]) {
                    result += '&'+vars[i];
                }
            }
            return result;
        }
    };

})();
