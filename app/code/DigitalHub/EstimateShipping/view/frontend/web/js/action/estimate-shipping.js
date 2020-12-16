define(
    [
        'mage/url',
        'jquery'
    ],
    function (url, $) {
        'use strict';
        return function (postcode, productId, qty, superGroupItems) {
            url.setBaseUrl(window.estimateShipping.base_url);

            return $.ajax({
                url: url.build('digitalhub_estimateshipping/estimate/post'),
                method: 'POST',
                type: 'JSON',
                data: {
                    postcode: postcode,
                    productId: productId,
                    qty: qty,
                    superGroupItems: superGroupItems
                }
            })
        };
    }
);
