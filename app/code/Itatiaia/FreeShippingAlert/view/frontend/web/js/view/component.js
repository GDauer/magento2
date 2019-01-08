define([
    'uiComponent',
    'jquery',
    'Itatiaia_FreeShippingAlert/js/action/validate',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function(Component, $, validate, quote, priceUtils){
    return Component.extend({
        defaults: {
            active: false,
            percentTotal: 0,
            difference: 0
        },
        initObservable: function () {
            this._super()
                .observe([
                    'active',
                    'percentTotal',
                    'difference'
                ]);
            return this;
        },
        initialize: function(){
            this._super();
            var self = this

            $.when(validate()).then(function(result){
                if(result){
                    self.active(parseInt(result.active))

                    if(result.trigger_subtotal){
                        self.updateTotal(result.trigger_subtotal,result.cart_subtotal)
                    }
                }
            })
        },
        updateTotal: function(trigger_subtotal, cart_subtotal){
            if(parseFloat(cart_subtotal) >= parseFloat(trigger_subtotal)){
                this.active(false);
                this.percentTotal(100);
                return;
            }
            var percent = parseInt((parseFloat(cart_subtotal) * 100) / parseFloat(trigger_subtotal))
            this.percentTotal(percent);
            var difference = Math.abs(parseFloat(trigger_subtotal) - parseFloat(cart_subtotal));
            this.difference(priceUtils.formatPrice(difference, quote.getPriceFormat()));
        }
    })
})
