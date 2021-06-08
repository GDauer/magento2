define([
    'jquery',
    'underscore',
    'Swissup_Firecheckout/js/utils/form-field/mask',
    'Swissup_Firecheckout/js/utils/form-field/placeholder'
    ], function ($, _, mask, placeholder) {
    'use strict';

    $(document).ready(function () {
        setInterval(function () {
            $('input[name="street[0]"]').attr('maxlength','60');
            $('input[name="street[1]"]').attr('maxlength','10');
            $('input[name="street[2]"]').attr('maxlength','40');
            $('input[name="street[3]"]').attr('maxlength','40');
        }, 5000);
    });

    var scopes = [
        '#co-shipping-form',
        'form[class*="address"]',
        '[class*="address-form"] form',
    ];

    _.each(scopes, function (scope) {
        mask(scope + ' [name="telephone"]',{
            guide: false,
            mask: function (raw) {
                var mask =['(',/\d/, /\d/,')',/\d/,/\d/,/\d/,/\d/,/\d/,'-',/\d/,/\d/,/\d/,/\d/];

                if (raw.length == 13) {
                    mask = ['(',/\d/, /\d/,')',/\d/,/\d/,/\d/,/\d/,'-',/\d/,/\d/,/\d/,/\d/];
                }

                return mask;
            }

        });

        mask(scope + ' [name="taxvat"]' + ', ' + scope + ' [name="vat_id"]', {
            guide: false,
            mask: function (raw) {
                var mask = [/\d/, /\d/,'.',/\d/,/\d/,/\d/,'.',/\d/,/\d/,/\d/,'/',/\d/,/\d/,/\d/,/\d/,'-',/\d/,/\d/];

                if (raw.length == 14) {
                    mask = [/\d/, /\d/, /\d/,'.',/\d/,/\d/,/\d/,'.',/\d/,/\d/,/\d/,'-',/\d/,/\d/];
                }

                return mask;
            }
        });
    });
    
    placeholder('[name="telephone"]','Telefone *');
    placeholder('#co-shipping-form [name="telephone"]','Celular *');
    placeholder('[name="postcode"]','CEP *');
    placeholder('[name="vat_id"]','CPF *');
});
