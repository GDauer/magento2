require([
  'jquery'
  ], function ($) {
    'use strict';

    const $cartForm = $('#form-validate');
    $cartForm.find('.control.qty input').on('keyup keypress', function(e) {
      const keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        $cartForm.find('button.action.update').click();
        return false;
      }
    });
});
