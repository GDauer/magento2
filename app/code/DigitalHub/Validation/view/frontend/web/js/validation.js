define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function($){
    'use strict';
    return function() {
        $.validator.addMethod(
            "taxvatvalidation",
            function(value, element) {
                var cpf = value.replace(/[\.-]+/g, "");
                var num, dig, sum, i, res, eqDigits;
                eqDigits = 1;
                if (cpf.length < 11)
                    return false;
                for (i = 0; i < cpf.length - 1; i++) {
                    if (cpf.charAt(i) != cpf.charAt(i + 1)) {
                        eqDigits = 0;
                        break;
                    }
                }
                if (!eqDigits) {
                    num = cpf.substring(0, 9);
                    dig = cpf.substring(9);
                    sum = 0;
                    for (i = 10; i > 1; i--) {
                        sum += num.charAt(10 - i) * i;
                    }
                    res = sum % 11 < 2 ? 0 : 11 - sum % 11;
                    if (res != dig.charAt(0)) {
                        return false;
                    }
                    num = cpf.substring(0, 10);
                    sum = 0;
                    for (i = 11; i > 1; i--) {
                        sum += num.charAt(11 - i) * i;
                    }
                    res = sum % 11 < 2 ? 0 : 11 - sum % 11;
                    return res == dig.charAt(1);
                }
                return false;
            },
            $.mage.__("O CPF informado é inválido.")
        );
    }
});