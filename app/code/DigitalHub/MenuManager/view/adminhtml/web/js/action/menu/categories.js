define(
    [
        'mage/storage',
        'mage/url'
    ],
    function (storage, url) {
        'use strict';
        return function () {
            url.setBaseUrl(window.menuManager.base_url);
            return storage.get(url.build('admin/digitalhub_menumanager/menu/categories'))
        };
    }
);
