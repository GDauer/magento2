define([
    'jquery'
], function($){
    return function(){
        return $.ajax({
            url: window.freeshippingalertConfig.queryUrl,
            method: 'GET',
            type: 'JSON'
        })
    }
})
