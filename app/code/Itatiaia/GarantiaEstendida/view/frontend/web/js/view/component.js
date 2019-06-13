define(
    ['jquery', 'ko', 'Magento_Ui/js/modal/modal', 'uiComponent'],
    function ($, ko, modal, Component) {
        return Component.extend({
            defaults: {
                title: 'GARANTIA ESTENDIDA GRATUITA'
            },
            initialize: function(){
                this._super();

            },
            afterRender: function(element){
                console.log('123');
                var options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'modal-garantia',
                    title: 'GARANTIA ESTENDIDA ITATIAIA',
                    buttons: false
                };
                var popup = modal(options, element);
                $('.garantia').click(function(){
                    $(element).modal('openModal');
                })
            },
        })
    })