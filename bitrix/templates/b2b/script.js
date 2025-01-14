document.addEventListener('DOMContentLoaded', function (){
    initProductDetail();
});


function initProductDetail() {
    let content = document.querySelector(".product-slide-info"),
		body = document.querySelector("body"),
		shade = document.querySelector(".shade");

    BX.bind(window, 'click', function(e){
        // content.classList.remove("show");
        if (content && content.classList.contains("show")) {
			let clickInside = e.composedPath().includes(content),
				clickInsideG = e.composedPath().includes(document.querySelector(".glightbox-container"));

			if (!clickInside && !clickInsideG) {
				content.classList.remove("show");
                body.classList.remove("noscroll");
                shade.classList.add("disable");
			}
		}
    });

    // BX.bind(content, 'click', function(event){
    //     event.stopPropagation();
    // });
};

function initDetailOpener(parentSelector) {

    let detailOpeners = document.querySelectorAll('.detail-opener-btn');
    let content = document.querySelector(".product-slide-info");
    let detailContainer = document.querySelector('.product-slide-content');

    detailOpeners.forEach(dOpener => {
        dOpener.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            let row = dOpener.closest(parentSelector);
           // let row = dOpener.closest('.product-table-row');

            if (row){
                let obDetailData = row.nextElementSibling;
                if (obDetailData){
                    let cloneNode = obDetailData.cloneNode(true);
                    detailContainer.innerHTML = '';
                    detailContainer.insertAdjacentElement('afterbegin', cloneNode);
                    BX.show(cloneNode);

                    initProductDetailSlider();

                    content.classList.add("show");
                    let closer = content.querySelector(".close");
                    BX.bind(closer, 'click', function(e){
                        e.preventDefault();
                        e.stopPropagation();
                        content.classList.remove("show");
                    });
                }

            }
        })
    });
};


function DarthFocus() {
    setTimeout(() => {
    var inputField = document.querySelector( '.bxmaker-authuserphone-input-phone__input [name="PHONE"]');
    if (inputField) {
        inputField.focus()
    }}, 100)
}

window.onload = DarthFocus;
