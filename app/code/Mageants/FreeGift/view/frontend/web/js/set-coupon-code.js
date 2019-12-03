define([
    'ko',
    'jquery',    
    'mage/url',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'Magento_Checkout/js/model/error-processor',
    'Magento_SalesRule/js/model/payment/discount-messages',
    'mage/storage',
    'mage/translate',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/full-screen-loader'
], function (ko, $, urlBuilder, quote, urlManager, errorProcessor, messageContainer, storage, $t, getPaymentInformationAction,
    totals, fullScreenLoader
) {
    'use strict';

    return function (couponCode, isApplied) {
        var quoteId = quote.getQuoteId(),
            url = urlManager.getApplyCouponUrl(couponCode, quoteId),
            message = $t('Your coupon was successfully applied 123.');

        fullScreenLoader.startLoader();

        return storage.put(
            url,
            {},
            false
        ).done(function (response) {
            var deferred;                        
            var couponUrl = urlBuilder.build("mageants_freegift/index/addcoupon");
            jQuery.ajax({
                type: "POST",
                url: couponUrl,
                data: {Id: couponCode},
                success:  function(data){
                   // window.location.reload(true);
                }
            });
            if (response) {
                deferred = $.Deferred();

                isApplied(true);
                totals.isLoading(true);
                getPaymentInformationAction(deferred);
                $.when(deferred).done(function () {
                    fullScreenLoader.stopLoader();
                    totals.isLoading(false);
                });
                messageContainer.addSuccessMessage({
                    'message': message
                });
            }
        }).fail(function (response) {
            fullScreenLoader.stopLoader();
            totals.isLoading(false);
            errorProcessor.process(response, messageContainer);
        });
    };
});
