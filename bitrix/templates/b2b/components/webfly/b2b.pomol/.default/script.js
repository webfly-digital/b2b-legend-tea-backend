(function () {
    'use strict';

    if (!!window.B2BPomolComponent)
        return;

    window.B2BPomolComponent = function () {

    };

    window.B2BPomolComponent.prototype = {
        init: function () {
            BX.bind(BX('catalog-pomol'), 'change', BX.proxy(this.order, this));
        },
        order: function () {
            let target = BX.proxy_context,
                activePomol = target.options[target.selectedIndex],
                pomol;

            if (activePomol) {
                pomol = activePomol.value;
            }
            let path = window.location.pathname,
                query = this.buildSortQuery(),
                sortLink;

            if (pomol) {
                sortLink = path + '?pomol=' + pomol + query;
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
                if (decodeURIComponent(pair[0]) != 'pomol' && vars[i]) {
                    result += '&' + vars[i];
                }
            }
            return result;
        }
    };

})();
