require([
    'jquery'
], function($){
    var productId = $('.product-add-form input[name=product]').val()
    var originalPrice = $('#product-price-' + productId).text()

    setInterval(function(){
        if($('#product-price-' + productId).text() != originalPrice){
            originalPrice = $('#product-price-' + productId).text();

            var price = $('#product-price-' + productId)
                    .text()
                    .trim()
                    .replace('R$','')
                    .replace('.','')
                    .replace(',','.');
            price = parseFloat(price);

            var url = BASE_URL + 'digitalhub_additionalprices/estimate?price=' + price;
            var originalPriceContainer = $('.product-info-price .additional-prices-container')
            $.getJSON(url, function(json){
                if(json && json.html){
                    originalPriceContainer.after(json.html)
                    originalPriceContainer.remove()
                }
            })
        }
    }, 100)
})
