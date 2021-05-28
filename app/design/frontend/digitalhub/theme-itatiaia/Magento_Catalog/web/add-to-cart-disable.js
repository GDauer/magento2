require([
    'jquery',
    'jquery/ui',
    'loader'
], function($) {
    'use strict';

    $(document).ready(function() {
        const addToCartButton = $("button.itatiaia-tocart"),
                addToCartForm = addToCartButton.parents("form");
        
        addToCartForm.on("submit", function() {
            if (addToCartForm.validation() && addToCartForm.validation("isValid") === true) {
                addToCartButton.prop("disabled", true);
                $('body').loader("show");
                addToCartForm.submit();
            }
        });
    });
});