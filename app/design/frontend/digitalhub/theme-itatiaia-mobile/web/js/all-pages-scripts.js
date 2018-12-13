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

  jQuery(document).ready(function() {
    jQuery('#tabs-list .nav-tabs > li > a').click(function(event){
    event.preventDefault();
    var active_tab_selector = jQuery('#tabs-list .nav-tabs > li.active > a').attr('href');
    var actived_nav = jQuery('#tabs-list .nav-tabs > li.active');
    actived_nav.removeClass('active');
    jQuery(this).parents('li').addClass('active');
    jQuery(active_tab_selector).removeClass('active');
    jQuery(active_tab_selector).addClass('hide');
    var target_tab_selector = jQuery(this).attr('href');
    jQuery(target_tab_selector).removeClass('hide');
    jQuery(target_tab_selector).addClass('active');
       });
  });
  jQuery(document).ready(function() {
    jQuery('a.show-filters').on('click', function(e) {
      jQuery('.block-content.filter-content').toggleClass("active");
      e.preventDefault();
    });
    jQuery('.cls-filters a.cls-btn').on('click', function(e) {
      jQuery('.block-content.filter-content').removeClass("active");
      e.preventDefault();
    });
  });
});
