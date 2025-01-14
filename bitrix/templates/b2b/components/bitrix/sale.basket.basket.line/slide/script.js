'use strict';

function BitrixSmallCart(){}

BitrixSmallCart.prototype = {

	activate: function ()
	{
        this.cartElement = BX(this.cartId);
        this.setCartBodyClosure = this.closure('setCartBody');
		BX.addCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {}));
	},

	closure: function (fname, data)
	{
		var obj = this;
		return data
			? function(){obj[fname](data)}
			: function(arg1){obj[fname](arg1)};
	},

	refreshCart: function (data)
	{
		data.sessid = BX.bitrix_sessid();
		data.siteId = this.siteId;
		data.templateName = this.templateName;
		data.arParams = this.arParams;
		BX.ajax({
			url: this.ajaxPath,
			method: 'POST',
			dataType: 'html',
			data: data,
			onsuccess: this.setCartBodyClosure
		});
	},

	setCartBody: function (result)
	{
		if (this.cartElement){
            this.cartElement.innerHTML = result.replace(/#CURRENT_URL#/g, this.currentUrl);
			this.initCartToggle();
		}
	},


	initCartToggle: function(){
		let cart = document.querySelector(".cart");

		document.querySelectorAll(".cart-toggler")
			.forEach(toggler => {
				if (cart) {
					if (!document.querySelector('.three-cols-table-container')) {
						cart.classList.add("desktop-slide")
					} else {
						toggler.classList.add("d-sm-desktop")
					}

					toggler.addEventListener("click", function () {
						event.preventDefault()
						cart.classList.toggle("show")
					})
				}
			})
	}
};
