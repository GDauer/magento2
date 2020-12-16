require([
    'jquery',
    'jquery/ui',
    'loader',
    'mage/translate'
], function($) {
    'use strict';

    $(document).ready(function() {
        $("button.itatiaia-tocart").on("click", function() {
            if ($(this).parents("form").validation() && $(this).parents("form").validation("isValid") === true) {
                $(this).prop("disabled", true);
                $('body').loader("show");
                $(this).parents("form").submit();
            }
        });
    });
});