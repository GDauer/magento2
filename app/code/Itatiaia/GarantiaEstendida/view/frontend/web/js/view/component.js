define(
    ['jquery', 'ko', 'Magento_Ui/js/modal/modal', 'uiComponent'],
    function ($, ko, modal, Component) {
        return Component.extend({

            initialize: function(config){
                this._super();
                this.title = ko.observable(config.title)
            },
            afterRender: function(element){
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