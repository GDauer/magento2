require([
  'jquery',
  'owlcarousel'
  ], function () {
    'use strict';

    jQuery.noConflict();

    jQuery(window).scroll(function () {
        var headerHeight = jQuery('.page-header').innerHeight();
        var contentHeight = jQuery('.column.main').innerHeight();
        var sidebarHeight = jQuery('.product-info-main').height();
        var sidebarBottomPos = contentHeight - sidebarHeight;
        var trigger = jQuery(window).scrollTop() - headerHeight;
        if (jQuery(window).scrollTop() >= headerHeight) {
            jQuery('.product-info-main').addClass('fixed');
        } else {
            jQuery('.product-info-main').removeClass('fixed');
        }

        if (trigger >= sidebarBottomPos) {
            jQuery('.product-info-main').addClass('bottom');
        } else {
            jQuery('.product-info-main').removeClass('bottom');
        }
    });

    // init
    jQuery(function(){
        jQuery(".product-items").addClass("owl-carousel");
        jQuery(".product-items").owlCarousel({
            loop: true,
            nav: true,
            dots: true,
            lazyLoad: true,
            margin: 0,
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

        initBuyBoxScroll();
    });

    function initBuyBoxScroll(){
        var topThreshold = jQuery('.column.main').offset().top;
        var fixedTopPosition = 80;
        jQuery('.column.main').css({position: 'relative'});
        jQuery('.product-info-main').css({transition: 'top 0.1s'})

        jQuery(window).on("scroll", function() {
            var maxBottom = jQuery('.column.main .product.info.detailed').offset().top + jQuery('.column.main .product.info.detailed').height()
            maxBottom = maxBottom - jQuery('.product-info-main').height() - fixedTopPosition;

            var fromTop = jQuery(window).scrollTop();
            if(fromTop > topThreshold){
                if(fromTop >= maxBottom){
                    jQuery('.product-info-main').css({position: '', top: '', bottom: ''})
                } else {
                    jQuery('.product-info-main').css({position: 'sticky', top: fixedTopPosition, transition: 0})
                }
                jQuery('.product-options-bottom .field.qty, .product-reviews-summary').hide();

                // só esconde o frete se não tiver sido calculado ainda
                if(!jQuery('.estimate-shipping-result-container').find('*').length){
                    jQuery('#digitalhub-estimateshipping').hide();
                } else {
                    jQuery('#digitalhub-estimateshipping').show();
                }
            } else {
                jQuery('.product-info-main').css({position: '', top: '', bottom: ''})
                jQuery('.product-options-bottom .field.qty, .product-reviews-summary').show();
                jQuery('#digitalhub-estimateshipping').show();
            }
        });
    }
});
