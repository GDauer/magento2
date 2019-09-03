require([
    'jquery',
    'DigitalHub_CustomerAttributes/js/jquery.mask.min'
], function($, inputMask){
    var intervalSearchEl = setInterval(function(){
        if($('input[name=vat_id]').length){
            clearInterval(intervalSearchEl);

            $('input[name=vat_id]').mask('000.000.000-00', {
                placeholder: "CPF *",
            });

            $('input[name="street[0]"]').attr("placeholder", "Endereço");
            $('input[name="street[1]"]').attr("placeholder", "Número");
            $('input[name="street[2]"]').attr("placeholder", "Complemento");
            $('input[name="street[3]"]').attr("placeholder", "Bairro");
            $('input[name="city"]').attr("placeholder", "Cidade");

            $('input[name=postcode]').mask('00000-000', {
                placeholder: "CEP *",
            });

            var TelephoneBehaviour = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            };
            var telOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(TelephoneBehaviour.apply({}, arguments), options);
                },
                placeholder: "Celular *"
            };
            $('input[name=telephone]').mask(TelephoneBehaviour, telOptions);
        }
    });
});
