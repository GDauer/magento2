require([
  'jquery',
  'owlcarousel'
  ], function () {
    'use strict';

    jQuery.noConflict();

    // jQuery(window).scroll(function () {
    //     var headerHeight = jQuery('.page-header').innerHeight();
    //     var contentHeight = jQuery('.column.main').innerHeight();
    //     var sidebarHeight = jQuery('.product-info-main').height();
    //     var sidebarBottomPos = contentHeight - sidebarHeight;
    //     var trigger = jQuery(window).scrollTop() - headerHeight;
    //     if (jQuery(window).scrollTop() >= headerHeight) {
    //         jQuery('.product-info-main').addClass('fixed');
    //     } else {
    //         jQuery('.product-info-main').removeClass('fixed');
    //     }
    //
    //     if (trigger >= sidebarBottomPos) {
    //         jQuery('.product-info-main').addClass('bottom');
    //     } else {
    //         jQuery('.product-info-main').removeClass('bottom');
    //     }
    // });

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
        if(jQuery('#grouped_items').length){
            // jQuery('.float-min').css({transition: 'top 0.1s'})

            jQuery(window).on("scroll", function() {
                var topThreshold = jQuery('#grouped_items').offset().top - jQuery('.wrp-header-content').height();
                var fromTop = jQuery(window).scrollTop();
                var newTopPosition = Math.abs(topThreshold - jQuery(window).scrollTop()) + jQuery('.wrp-header-content').height();
                var maxBottom = jQuery('#grouped_items').height() + jQuery('#grouped_items').offset().top - 50
                maxBottom = maxBottom - jQuery('.float-min').height();

                if(fromTop > topThreshold){
                    if(fromTop >= maxBottom){
                        // jQuery('.float-min').css({top: ''})
                    } else {
                        jQuery('.float-min').css({top: newTopPosition, transition: 0})
                    }
                } else {
                    jQuery('.float-min').css({top: ''})
                }
            });
        }
    }

		jQuery(function(){
			jQuery("#tab-label-additional").prependTo(".product.data.items");
			jQuery("#additional").prependTo(".product.data.items");

		});

});
