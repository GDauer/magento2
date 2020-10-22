require([
  'jquery',
  'owlcarousel'
  ], function () {
    'use strict';
    jQuery.noConflict();
    jQuery(".main-banners.owl-carousel").owlCarousel({
      loop: true,
      nav: false,
      dots: true,
      autoplay: true,
      autoplayTimeout: 5000,
      responsive:{
        0:{
          items:1
        },
        600:{
          items:1
        },
        1000:{
          items:1
        }
      }
  })
});
