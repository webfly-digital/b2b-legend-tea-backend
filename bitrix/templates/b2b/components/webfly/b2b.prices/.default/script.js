(function () {
    'use strict';

    if (!!window.B2BPricesComponent)
        return;

    window.B2BPricesComponent = function () {

    };

    window.B2BPricesComponent.prototype = {
        init: function () {
            BX.bind(BX('selectedPrices'), 'change', BX.proxy(this.price, this));
        },
        price: function () {
            let target = BX.proxy_context,
                activePrice = target.options[target.selectedIndex],
                price;

            if (activePrice) price = activePrice.value;

            let path = window.location.pathname,
                query = this.buildQuery(),
                link;

            if (price) {
                link = path + '?price=' + price + query;
            } else {
                if (query)
                    link = path + '?' + query.substring(1);
                else
                    link = path;
            }
            window.location.href = link;
        },
        buildQuery: function () {
            let query = window.location.search.substring(1);
            let vars = query.split('&'),
                result = '';
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                if (decodeURIComponent(pair[0]) != 'price' && vars[i]) {
                    result += '&' + vars[i];
                }
            }
            return result;
        }
    };

})();
