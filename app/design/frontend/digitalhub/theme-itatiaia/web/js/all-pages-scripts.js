require([
  'jquery',
  'owlcarousel'
], function ($) {
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

    jQuery(document).ready(function() {
      jQuery('#tabs-att .nav-tabs > li > a').click(function(event){
      event.preventDefault();
      var active_tab_selector = jQuery('#tabs-att .nav-tabs > li.active > a').attr('href');
      var actived_nav = jQuery('#tabs-att .nav-tabs > li.active');
      actived_nav.removeClass('active');
      jQuery(this).parents('li').addClass('active');
      jQuery(active_tab_selector).removeClass('active');
      jQuery(active_tab_selector).addClass('hide');
      var target_tab_selector = jQuery(this).attr('href');
      jQuery(target_tab_selector).removeClass('hide');
      jQuery(target_tab_selector).addClass('active');
         });
    });

    /*--Landingpage BBB --*/
    jQuery(".main-banners-landingpage.owl-carousel").owlCarousel({
      loop: true,
      nav: true,
      dots: true,
      lazyLoad: true,
      margin: 0,
      items: 1
    })
    jQuery(".products-carousel.owl-carousel").owlCarousel({
      center: true,
      items: 4,
      loop: true,
      nav: true,
      dots: false,
      margin: 60,
      responsive: {
        0:{
          items: 1
        },
        600:{
          items: 2
        },
        1000:{
          items: 4
        }
      }
    })

    var time = setInterval(function(){
      jQuery(window).scroll(function() {
       if (jQuery(this).scrollTop() > 158){
          jQuery('.landingpage-bbb20 .wrp-header').addClass("sticky");
        }
        else{
          jQuery('.landingpage-bbb20 .wrp-header').removeClass("sticky");
        }
      });
    }, 100);

    jQuery(".wrp-descricao-produto .carousel-images").owlCarousel({
      loop: true,
      nav: false,
      dots: true,
      lazyLoad: true,
      margin: 0,
      items: 1
    });
    /*--Landingpage BBB --*/


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

    jQuery(function(){
        if(jQuery('body.catalog-category-view').length || jQuery('body.cms-index-index').length || jQuery('body.catalogsearch-result-index').length){
            if(!window.__decimalsRemoved){
                jQuery('.price-wrapper').each(function(){
                    var span = jQuery(this).find('span.price')
                    var text = span.text()
                    text = text.split(',')[0]
                    span.html(text)
                    span.css('visibility', 'visible')
                })
                window.__decimalsRemoved = true;
            }
        }
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

// limpeza do carrinho a partir do minicart
function emptyCartFromMinicart()
{
    if(confirm('Tem certeza que deseja remover todos os itens do seu carrinho de compras?')){
        jQuery.post(BASE_URL + 'checkout/cart/updatePost', {
            update_cart_action: 'empty_cart',
            form_key: jQuery.cookie('form_key')
        })
    }
    return false;
}
