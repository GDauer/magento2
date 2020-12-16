define([
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Itatiaia_Custom/js/model/isCpfValid'
    ], function (Component, additionalValidators, isCpfValid) {
        'use strict';
        additionalValidators.registerValidator(isCpfValid);
        return Component.extend({});
    }
);
