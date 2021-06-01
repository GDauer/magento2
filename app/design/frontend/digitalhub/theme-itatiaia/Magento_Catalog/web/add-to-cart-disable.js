require([
    'jquery',
    'jquery/ui',
    'loader'
], function($) {
    'use strict';

    $(document).ready(function() {
        const addToCartButton = $("button.itatiaia-tocart"),
                addToCartForm = addToCartButton.parents("form"),
                prodQty = addToCartForm.find('#qty');
        
        addToCartForm.on("submit", function() {
            if (addToCartForm.validation() && addToCartForm.validation("isValid") === true && prodQty.val() > 0) {
                addToCartButton.prop("disabled", true);
                $('body').loader("show");
                addToCartForm.submit();
            }
        });
    });
});