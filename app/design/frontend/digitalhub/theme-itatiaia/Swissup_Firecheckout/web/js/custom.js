define([
    'jquery'
], function($) {
    'use strict';

    $(document).ready(function () {
        setInterval(function () {
            $('input[name="street[0]"]').attr('maxlength','60');
            $('input[name="street[1]"]').attr('maxlength','10');
            $('input[name="street[2]"]').attr('maxlength','10');
            $('input[name="street[3]"]').attr('maxlength','40');
        }, 5000);
    });
});
