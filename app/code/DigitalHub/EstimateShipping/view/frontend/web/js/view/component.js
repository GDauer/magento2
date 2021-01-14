define([
    'uiComponent',
    'jquery',
    'DigitalHub_EstimateShipping/js/jquery.mask.min',
    'DigitalHub_EstimateShipping/js/action/estimate-shipping'
], function(Component, $, maskedInput, estimateShippingAction){
    'use strict';
    return Component.extend({
        defaults: {
            postcode: '',
            productId: '',
            showSelectionAlert: false,
            errorMessage: '',
            showLoader: false,
            estimateResult: null
        },
        initObservable: function () {
            this._super()
                .observe([
                    'postcode',
                    'productId',
                    'showSelectionAlert',
                    'errorMessage',
                    'showLoader',
                    'estimateResult'
                ]);
            return this;
        },
        initialize: function(){
            var self = this;
            self._super();
        },
        afterRender: function(){
            var self = this;
            jQuery('input#estimate-shipping-postcode').mask('00000-000',{placeholder: "_____-___"});
            jQuery('input#estimate-shipping-postcode').keydown(function(e){
                if(e.keyCode === 13){
                    jQuery('input#estimate-shipping-postcode').blur()
                    self.estimateShipping();
                    e.preventDefault();
                    return false;
                }
            });
            jQuery('input#estimate-shipping-postcode').keyup(function(e){
                if(jQuery(this).val().length == 9){
                    jQuery('input#estimate-shipping-postcode').blur()
                    self.estimateShipping();
                    e.preventDefault();
                    return false;
                }
            });
        },
        estimateShipping: function(){
            var self = this;
            var productId = this.getProductId();
            var qty = parseInt($('input#qty').val()) ? $('input#qty').val() : 1;

            if(!this.validatePostcode()){
                this.errorMessage('Insira um CEP válido');
                $('input#estimate-shipping-postcode').focus();
            } else {
                this.errorMessage(null);

                // grouped products
                if($('input[name^=super_group]').length){
                    var products = []
                    var min_selection = false;
                    $('input[name^=super_group]').each(function(){
                        var id = parseInt($(this).attr('name').replace(/\D+/,''))
                        var qty = parseInt($(this).val())
                        products.push({id: id, qty: qty})

                        if(qty){
                            min_selection = true;
                        }
                    })

                    console.log(products);

                    if(min_selection){
                        this.showSelectionAlert(false)
                        this.showLoader(true)
                        self.estimateResult(null)
                        $.when(estimateShippingAction(this.postcode(), productId, qty, products)).then(function(result){
                            self.showLoader(false)
                            self.errorMessage(null)
                            if(result.error){
                                self.errorMessage(result.error)
                            } else {
                                self.estimateResult(result.items)
                            }
                        });
                    } else {
                        this.showSelectionAlert(true)
                    }
                } else {
                    // other products
                    if(productId){
                        this.showSelectionAlert(false)
                        this.showLoader(true)
                        self.estimateResult(null)
                        $.when(estimateShippingAction(this.postcode(), productId, qty)).then(function(result){
                            self.showLoader(false)
                            self.errorMessage(null)
                            if(result.error){
                                self.errorMessage(result.error)
                            } else {
                                self.estimateResult(result.items)
                            }
                        });
                    } else {
                        this.showSelectionAlert(true)
                    }
                }
            }
        },
        validatePostcode: function(){
            var postcode = this.postcode().replace(/\D+/,'');
            if(!postcode) return false;
            if(postcode.length != 8) return false;
            return true;
        },
        closeResults: function(){
            $('.estimate-shipping-result').hide();
            return false;
        },
        getProductId: function(){

            if($('div.swatch-attribute').length){
                // Configurable product
                var selected_options = {};
                $('div.swatch-attribute').each(function(k,v){
                    var attribute_id    = $(v).attr('attribute-id');
                    var option_selected = $(v).attr('option-selected');
                    if(!attribute_id || !option_selected){ return;}
                    selected_options[attribute_id] = option_selected;
                });

                var product_id_index = $('[data-role=swatch-options]').data('mageSwatchRenderer').options.jsonConfig.index;
                var found_ids = [];
                $.each(product_id_index, function(product_id,attributes){
                    var productIsSelected = function(attributes, selected_options){
                        return _.isEqual(attributes, selected_options);
                    }
                    if(productIsSelected(attributes, selected_options)){
                        found_ids.push(product_id);
                    }
                });

                return found_ids ? found_ids[0] : null;
            } else {
                // Simple product
                return $('input[name=product]').val()
            }

        }
    });
});
