require([
  'jquery'
  ], function () {
  'use strict';
  jQuery.noConflict();
  jQuery(document).ready(function() {
    jQuery('.block.account-nav').append('<button class="read-more">Opções avançadas</button>');
    jQuery('.block.account-nav .read-more').click(function(){
      jQuery('.account-nav-content').toggleClass('expanded');
    });
    jQuery('.block.account-nav .read-more').click(function(){
      if (jQuery('.read-more').text() == "Opções avançadas") {
        jQuery('.read-more').text("Fechar opções avançadas");
      }
      else {
       jQuery('.read-more').text("Opções avançadas");
      }
    });
	});
});
