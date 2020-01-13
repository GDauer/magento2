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
      });

      jQuery('.wrp-vantagens .itens-vantagens').owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        lazyLoad: true,
        dotsEach: 1,
        slideBy: 1,
        autoplay: true,
        margin: 20,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
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
        jQuery('a.show-filters').on('click', function(e) {
          jQuery('.block-content.filter-content').toggleClass("active");
          e.preventDefault();
        });
        jQuery('.cls-filters a.cls-btn').on('click', function(e) {
          jQuery('.block-content.filter-content').removeClass("active");
          e.preventDefault();
        });
      });
/*

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
*/

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
      });

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


// layered nav com filtros aplicados após click em botão
window._layeredFilters = {}
function _toggleFilterOption(){}
