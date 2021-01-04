require([
  'jquery'
], function($){
  function updateActiveStep(){
    $('.checkout-steps li').removeClass('active');

    if(document.location.hash == '#shipping'){
      $('.checkout-steps li.step-identification').addClass('complete');
    }

    if(document.location.hash == '#payment'){
      $('.checkout-steps li.step-shipping').addClass('complete');
      $('.checkout-steps li.step-identification').addClass('complete');
    }
  }
  $(function(){
    updateActiveStep();
    window.onhashchange = updateActiveStep;
  });
});
