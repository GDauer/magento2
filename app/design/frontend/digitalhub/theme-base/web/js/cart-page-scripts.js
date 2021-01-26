require([
  'jquery',
  'owlcarousel'
  ], function () {
    'use strict';

    jQuery.noConflict();

    // init
    jQuery(function(){
        jQuery(".products-grid .product-items").addClass("owl-carousel");
        jQuery(".products-grid .product-items").owlCarousel({
            loop: true,
            nav: true,
            dots: true,
            lazyLoad: true,
            margin: 20,
            responsive:{
                '0': {
                    items: 2
                },
                '600': {
                    items: 4
                },
                '1000': {
                    items: 4
                }
            }
        })
    });

});
