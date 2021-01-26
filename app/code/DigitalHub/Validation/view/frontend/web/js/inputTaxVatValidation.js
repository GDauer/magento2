require([
    'jquery',
    'jquery/validate'
], function($){
    $(document).ready(function() {
        $('input[name="taxvat"]').blur(function() {
            $('input[name="taxvat"]').validation();
            $('input[name="taxvat"]').validation('isValid');
        });
        $('input[name="vat_id"]').blur(function() {
            $('input[name="vat_id"]').validation();
            $('input[name="vat_id"]').validation('isValid');
        });
    });
});
