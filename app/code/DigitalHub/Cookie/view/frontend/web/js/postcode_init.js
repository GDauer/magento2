define([
        'ko',
        'jquery',
        'jquery/jquery.cookie',
    ],
    function (ko, $) {
        "use strict";
        ko.bindingHandlers.postcodeBinding = {
            init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
                let cookie = $.cookie('postcode');
                if (null === cookie) {
                    return;
                }
                $(element).val(cookie).trigger('change');
            }
        };
    });
