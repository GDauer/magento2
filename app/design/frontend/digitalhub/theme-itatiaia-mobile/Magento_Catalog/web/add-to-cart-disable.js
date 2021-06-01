require([
    'jquery', 
    'loader'
], function($) {
    $('button.itatiaia-tocart').mousedown(function() {
       $(this).prop("disabled", true);
       $('body').loader('show');
       $(this).parents('form').submit();
    });
});