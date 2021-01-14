require([
    'jquery',
    'DigitalHub_CustomerAttributes/js/jquery.mask.min'
], function($, inputMask){
    var intervalSearchEl = setInterval(function(){
        if($('input[name=vat_id]').length){
            clearInterval(intervalSearchEl);

            $('input[name=vat_id]').mask('000.000.000-00', {
                placeholder: "CPF *",
            }).change(function(){
                if(!$(this).val()){
                    console.log(4234)
                }
            })
        }
    });
});
