require([
  'jquery'
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
});
