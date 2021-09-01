require([
    'jquery',
    'domReady',
    'jquery/ui',
    'loader',
], function($, domReady) {
    'use strict';

    domReady(function () {
        const addToCartButton = $("button.itatiaia-tocart"),
        addToCartForm = addToCartButton.parents("form"),
        prodQty = addToCartForm.find('#qty');

        addToCartForm.on("submit", function() {
            if (addToCartForm.validation() && addToCartForm.validation("isValid") === true && prodQty.val() > 0) {
                addToCartButton.prop("disabled", true);
                $('body').loader("show");
                addToCartForm.submit();
            }

            if(addToCartForm.validation("isValid") === true)
               $('body').trigger('processStart');
        });

        addToCartButton.attr('disabled', false);

    });
  
});