require([
  'jquery'
], function($){
  function updateActiveStep(){
    $('.checkout-steps li').removeClass('active');

    if(document.location.hash == '#customer-email' || document.location.hash == "#" || !document.location.hash){
      //$('.checkout-steps li.step-identification').addClass('active');
    }

    if(document.location.hash == '#shipping'){
      //$('.checkout-steps li.step-shipping').addClass('active');
      $('.checkout-steps li.step-identification').addClass('complete');
    }

    if(document.location.hash == '#payment'){
      $('.checkout-steps li.step-shipping').addClass('complete');
      $('.checkout-steps li.step-identification').addClass('complete');
      //$('.checkout-steps li.step-payment').addClass('active');
    }
  }
  $(function(){
    updateActiveStep();
    window.onhashchange = updateActiveStep
  })
})
