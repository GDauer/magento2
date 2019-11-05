define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function($){
    'use strict';
    return function() {
        $.validator.addMethod(
            "installmentsvalidation",
            function(value, element) {
                if ( value == -1)
                    return  false;
                else
                    return true;
            },
            $.mage.__("Escolha o número de parcelas.")
        );
    }
});