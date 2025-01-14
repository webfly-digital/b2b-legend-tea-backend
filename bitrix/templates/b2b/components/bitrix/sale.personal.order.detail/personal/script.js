BX.namespace('BX.Sale.PersonalOrderComponent');

(function() {
	BX.Sale.PersonalOrderComponent.PersonalOrderDetail = {
		init : function(params)
		{
			var listPaymentWrapper = document.getElementsByClassName('sale-order-detail-payment-options-methods');


			Array.prototype.forEach.call(listPaymentWrapper, function(paymentWrapper)
			{
				BX.bindDelegate(paymentWrapper, 'click', { 'class': 'active-button' }, BX.proxy(function()
				{
					BX.toggleClass(paymentWrapper, 'sale-order-detail-active-event');
				}, this));

			});

		}
	};
})();