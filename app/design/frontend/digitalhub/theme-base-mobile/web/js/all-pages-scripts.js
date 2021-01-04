require(['jquery','owlcarousel'],function(){
  'use strict';
  jQuery('.slider-marcas.owl-carousel').owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    lazyLoad: true,
    margin: 20,
    //center: true,
    responsive:{
      0:{
          items: 2
      },
      600:{
          items: 3
      },
      1000:{
          items: 7
      }
    }
  })
});
