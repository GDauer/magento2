/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */


 define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'mage/translate'
], function (Component, quote, priceUtils, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Tax/checkout/shipping_method/price'
        },
        isDisplayShippingPriceExclTax: window.checkoutConfig.isDisplayShippingPriceExclTax,
        isDisplayShippingBothPrices: window.checkoutConfig.isDisplayShippingBothPrices,

        /**
         * @param {Object} item
         * @return {Boolean}
         */
        isPriceEqual: function (item) {
            return item['price_excl_tax'] != item['price_incl_tax']; //eslint-disable-line eqeqeq
        },

        /**
         * @param {*} price
         * @return {*|String}
         */
        getFormattedPrice: function (price) {
            //todo add format data

            var finalPrice = priceUtils.formatPrice(price, quote.getPriceFormat());

            if  (finalPrice == "R$0,00") {
                return $t("FREE SHIPPING!");
            }

            return finalPrice;
        }
    });
});
