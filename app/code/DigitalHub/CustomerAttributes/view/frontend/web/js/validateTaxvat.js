
define([
    'jquery',
    'mage/url'
], function ($, urlBuilder) {
    'use strict';

    return {

        /**
         * Send the data through AJAX to given URL.
         *
         * @param data
         */
        init: async function (data) {
            
            let resp = false;
            await $.ajax({
                url: urlBuilder.build('customerVerify/ajax/verify'),
                data: {"taxvat": data},
                type: 'post',
                dataType: 'json',

                success: function (response) {
                    if (response.error) 
                       resp = response.error;

                },
                error: function (response) {
                    console.log(response);
                    
                }
            })

            return resp;

        }

    }
})