/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2021 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

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
