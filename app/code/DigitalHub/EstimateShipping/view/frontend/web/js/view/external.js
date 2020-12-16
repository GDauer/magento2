define([
    'uiComponent',
    'DigitalHub_EstimateShipping/js/view/component',
    'jquery'
], function(Component, EstimateComponent, $){
    'use strict';
    return EstimateComponent.extend({
        getProductId: function(){
            return window.estimateShipping.productId;
        }
    });
});
