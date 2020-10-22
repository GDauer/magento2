define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Catalog/js/price-utils'
], function(Component, ko, $, modal, priceUtils){
    return Component.extend({
        default: {
            finalPrice: null,
            lastPrice: 0,
            showInstallments: false
        },
        initObservable: function () {
            this._super()
                .observe([
                    'finalPrice',
                    'lastPrice',
                    'showInstallments'
                ]);

            return this;
        },
        initialize: function(){
            this._super();
            var self = this;

            this.installments = ko.observableArray([]);

            // console.log('installments', this.installments, this.installments());
            //
            // console.log('Initilize installments table')

            var productId = $('form#product_addtocart_form input[name=product]').val()
            setInterval(function(){
                var price = $('#product-price-' + productId)
                                .text()
                                .replace('R$', '')
                                .replace('.', '')
                                .replace(',','.')
                price = parseFloat(price)

                if(self.lastPrice() != price){
                    self.lastPrice(price)
                    self.finalPrice(price)
                    console.log(price)
                }
            }, 100)

            self.finalPrice.subscribe(function(value){
                // console.log('Novo preço', value)
                self.updateInstallmentsConfig()
            })

            // this.updateInstallmentsConfig();
        },
        afterRender: function(element){
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                modalClass: 'modal-parcelamento',
                title: 'Opções de parcelamento',
                buttons: false
            };
            var popup = modal(options, element);
            $('.parc-link a').click(function(){
                $(element).modal('openModal');
            })
        },
        updateInstallmentsConfig: function(){
            // console.log('updateInstallmentsConfig')

            var self = this;
            var priceFormat = {"pattern":"R$ %s","precision":2,"requiredPrecision":2,"decimalSymbol":",","groupSymbol":".","groupLength":3,"integerRequired":false};
            var cards = [];

            for(var key in window.installmentsConfig){
                installments = []
                // console.log('Parcelamento para', key)

                for(var maxValueForNumber in window.installmentsConfig[key]){
                    var number = window.installmentsConfig[key][maxValueForNumber];
                    if(self.calcInstallmentValue(number, maxValueForNumber)){
                        var value = priceUtils.formatPrice(self.calcInstallmentValue(number, maxValueForNumber), priceFormat)
                        installments.push({number: number, value: value})
                    }
                }

                cards.push({
                    label: self.getCardLabel(key),
                    installments: installments
                })
            }

            // console.log(cards)
            self.installments(cards)
            if(cards.length){
                self.showInstallments(true)
            }
        },
        calcInstallmentValue: function(number, maxValueForNumber){
            var self = this;
            // console.log(number, maxValueForNumber)
            if(self.finalPrice() >= maxValueForNumber || number == 1){
                return self.finalPrice() / number;
            }
            return false;
        },
        getCardLabel: function(key){
            var map = {
                'VI': 'Visa',
                'MC': 'Mastercard',
                'DI': 'Discover',
                'DN': 'Diners',
                'AE': 'American Express',
                'EL': 'Elo',
                'HI': 'Hipercard'
            }
            if(typeof map[key] != undefined){
                return map[key]
            }
            return key;
        }
    })
})
