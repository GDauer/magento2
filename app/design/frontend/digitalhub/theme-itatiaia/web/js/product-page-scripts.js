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
            var topThreshold = jQuery('#grouped_items').offset().top;
            // jQuery('.float-min').css({transition: 'top 0.1s'})

            jQuery(window).on("scroll", function() {
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

    // jQuery(document).ready(function() {
    //    jQuery('.min-dtls .nav-tabs > li > a').click(function(event){
    //        event.preventDefault();
    //        var active_tab_selector = jQuery('.min-dtls .nav-tabs > li.active > a').attr('href');
    //        var actived_nav = jQuery('.min-dtls .nav-tabs > li.active');
    //        actived_nav.removeClass('active');
    //        jQuery(this).parents('.min-dtls .nav-tabs > li').addClass('active');
    //        jQuery(active_tab_selector).removeClass('active');
    //        jQuery(active_tab_selector).addClass('hide');
    //        var target_tab_selector = jQuery(this).attr('href');
    //        jQuery(target_tab_selector).removeClass('hide');
    //        jQuery(target_tab_selector).addClass('active');
    //     });
    // });
});
