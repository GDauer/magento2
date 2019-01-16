require([
  'jquery',
  'owlcarousel'
  ], function () {
    'use strict';
    jQuery.noConflict();

    jQuery(document).ready(function() {
        jQuery(window).on("scroll", function() {
            var fromTop = jQuery(window).scrollTop();
            jQuery(".page-header").toggleClass("down", (fromTop > 124));
        });
    });

    jQuery(".icon-menu").click(function() {
        jQuery(".page-header.down .container-menumanager-menu").toggleClass("open");
        jQuery(this).toggleClass("open-menu-icon");
    });

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


    // verificação de quantidade para grouped locked items
    jQuery(function(){
        jQuery('.checkout-cart-index .cart.item input[name^=cart]').keyup(function(){
            // se é um item de um agrupado
            if(jQuery(this).attr('rel').split('grouped_').length){
                var groupedId = jQuery(this).attr('rel').split('grouped_')[1];
                var qty = parseInt(jQuery(this).val());
                if(qty){
                    jQuery('.checkout-cart-index input[rel=grouped_'+groupedId+']').val(qty);
                }
            }
        })
    })
});


// verificações de remoção para grouped locked items
function confirmRemoveLockItem(target){
    if(!confirm('Este item só pode ser vendido com todo o conjunto. Se você removê-lo do seu carrinho, irá remover todos os itens deste conjunto. Deseja mesmo prosseguir?')){
        return false;
    }
    var removePostJson = jQuery(target).attr('data-post_locked');
    jQuery(target).attr('data-post', removePostJson);
    return true;
}
