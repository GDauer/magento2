require([
  'jquery',
  'owlcarousel'
  ], function () {
    'use strict';
    jQuery.noConflict();
    jQuery(".main-banners.owl-carousel").owlCarousel({
      loop:true,
      nav:true,
      autoplay:true,
      autoplayTimeout:5000,
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
  jQuery(".main-banners.owl-carousel li img").each(function(i, elem) {
    var img = jQuery(elem);
    var div = jQuery("<span class='wrp-image' />").css({
      background: "url(" + img.attr("src") + ") no-repeat top center",
      width: img.width() + "px",
      height: img.height() + "px"
    });
    img.replaceWith(div);
  });
});
