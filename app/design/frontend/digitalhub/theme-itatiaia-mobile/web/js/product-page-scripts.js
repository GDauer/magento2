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
    jQuery(document).ready(function() {
     jQuery(".product-items").addClass("owl-carousel");
    });
    jQuery(".product-items").owlCarousel({
      loop: true,
      nav: false,
      dots: true,
      lazyLoad: true,
      //center: true,
      margin: 20,
      responsive:{
        0:{
          items:2
        },
        600:{
          items:4
        },
        1000:{
          items:4
        }
      }
    })
});
