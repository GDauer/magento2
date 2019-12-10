define([
    'ko',
    'uiComponent',
    'jquery',
    'DigitalHub_MenuManager/js/jstree/dist/jstree',
    'Magento_Ui/js/modal/modal',
    'DigitalHub_MenuManager/js/action/menu/categories',
    'DigitalHub_MenuManager/js/action/menu/static-blocks',
    'DigitalHub_MenuManager/js/action/menu/cms-pages'
], function(ko, Component, $, jstree, modal, categories, staticBlocks, cmsPages) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DigitalHub_MenuManager/menu/editor',
            menuJsonData: null,
            currentNodeEdit: null
        },

        initObservable: function () {
            this._super()
                .observe([
                    'menuJsonData',
                    'currentNodeEdit'
                ]);
            return this;
        },

        initialize: function(){
            var self = this;
            window.menuManagerEditorScope = self;

            self.initMenuRender();

            this._super();
        },

        initMenuRender: function(){
            var self = this;

            var savedData = $('[name="menu[json_data]"]').val() ? JSON.parse($('[name="menu[json_data]"]').val()) : null;

            $('.tree-container').jstree({
                core: {
                    expand_selected_onload: false,
                    data: savedData,
                    check_callback: true
                },
                plugins: ['html_data','dnd','changed','contextmenu'],
                contextmenu: {
                    items: function($node){
                        var tree = $(".tree-container").jstree(true);
                        return {
                            create: {
                                separator_before: false,
                                separator_after: false,
                                label: "Adicionar item filho",
                                action: function (obj) {
                                    $node = tree.create_node($node, 'Novo item');
                                    tree.edit($node);
                                    self.updateJsonData();
                                }
                            },
                            rename: {
                                separator_before: false,
                                separator_after: false,
                                label: "Renomear",
                                action: function (obj) {$.modal
                                    tree.edit($node);
                                    self.updateJsonData();
                                }
                            },
                            remove: {
                                separator_before: false,
                                separator_after: false,
                                label: "Remover",
                                action: function (obj) {
                                    tree.delete_node($node);
                                    self.updateJsonData();
                                }
                            },
                            properties: {
                                separator_before: true,
                                separator_after: false,
                                label: "Editar propriedades",
                                action: function (obj) {
                                    self.currentNodeEdit($node);
                                    self.openEditModal();
                                }
                            }
                        };
                    }
                }
            }).on('ready.jstree', function(){ $(this).jstree('close_all') });

            $('.tree-container').on('changed.jstree', function(e, node_event){
                self.updateChangedNode(e, node_event);
            })

            $('.tree-container').on('rename_node.jstree', function(){
                self.updateJsonData();
            })

            $('#edit-modal').modal({
                buttons: [
                    {
                        text: 'Ok',
                        class: '',
                        click: function() {
                            self.updateNodeData();
                            this.closeModal();
                        }
                    }
                ]
            })
        },

        updateChangedNode: function(e, node_event){
            var self = this
            // console.log(node_event)
            // self.updateJsonData();
        },

        addMenuItem: function(){
            var self = this;
            var $node = $('.tree-container').jstree('create_node', null, "Novo item", 'last');
            $('.tree-container').jstree(true).edit($node);
            self.updateJsonData()
        },

        getItemDataOnly: function(itemData){
            var data = JSON.parse(JSON.stringify(itemData))
            delete data.children;
            return JSON.stringify(data);
        },

        openEditModal: function(){
            var node = this.currentNodeEdit();
            var data = node.data || {};
            var bodyLoader = jQuery('body').loader();
            bodyLoader.loader('show');

            // Data values
            $('#edit-type option[value="'+data.type+'"]').attr('selected','selected');
            $('#edit-url').val(data.url);
            if(data.use_submenu_static_block){
                $('#edit-use-static-block').attr('checked','checked');
            } else {
                $('#edit-use-static-block').removeAttr('checked');
            }
            if(data.use_subcategories_tree){
                $('#edit-use-subcategories-tree').attr('checked','checked');
            } else {
                $('#edit-use-subcategories-tree').removeAttr('checked');
            }
            $('#edit-modal').modal('openModal');

            // Verify static block
            function verifyStaticBlock(){
                if($('#edit-use-static-block:checked').length){
                    $('#edit-static-block').parents('.field').show();
                } else {
                    $('#edit-static-block').parents('.field').hide();
                }
            }
            verifyStaticBlock();
            $('#edit-use-static-block').change(verifyStaticBlock)

            // Verify type
            function verifyType(){
                $('#edit-category-id, #edit-cms-page-id, #edit-url, #edit-use-subcategories-tree').parents('.field').hide();
                if($('#edit-type').val() == 'category'){
                    $('#edit-category-id').parents('.field').show();
                    $('#edit-use-subcategories-tree').parents('.field').show();
                }
                if($('#edit-type').val() == 'cms'){
                    $('#edit-cms-page-id').parents('.field').show();
                }
                if($('#edit-type').val() == 'url'){
                    $('#edit-url').parents('.field').show();
                }
            }
            verifyType();
            $('#edit-type').change(verifyType);

            // Call dropdown options
            $.when(categories(), staticBlocks(), cmsPages()).done(function(categories_result, static_blocks_result, cms_pages_result){
                function _spaces(num)
                {
                    var str = '';
                    var total = num - 2; // start from 2
                    for(var i = 0; i<total; i++){ str += '|--'; }
                    return str;
                }

                // category options
                var categoriesOptions = '<option value=""> --- </option>';
                if(categories_result[0].items){
                    _.forEach(categories_result[0].items, function(item){
                        categoriesOptions+= '<option value="'+item.entity_id+'">'+_spaces(item.level)+item.name+'</option>';
                    })
                }
                $('#edit-category-id').html(categoriesOptions);
                $('#edit-category-id option[value="'+data.category_id+'"]').attr('selected','selected');

                // static block options
                var staticBlocksOptions = '<option value=""> --- </option>';
                if(static_blocks_result[0].items){
                    _.forEach(static_blocks_result[0].items, function(item){
                        staticBlocksOptions+= '<option value="'+item.block_id+'">'+item.title+'</option>';
                    })
                }
                $('#edit-static-block').html(staticBlocksOptions);
                $('#edit-static-block option[value="'+data.submenu_static_block_id+'"]').attr('selected','selected');

                // cms page options
                var cmsPageOptions = '<option value=""> --- </option>';
                if(cms_pages_result[0].items){
                    _.forEach(cms_pages_result[0].items, function(item){
                        cmsPageOptions+= '<option value="'+item.page_id+'">'+item.title+'</option>';
                    })
                }
                $('#edit-cms-page-id').html(cmsPageOptions);
                $('#edit-cms-page-id option[value="'+data.cms_page_id+'"]').attr('selected','selected');

                bodyLoader.loader('hide');
            })

            // Somente items level top tem submenu
            if(node.parents.length > 1){
                $('.edit-submenu-section').hide();
            } else {
                $('.edit-submenu-section').show();
            }
        },

        updateNodeData: function(){
            var data = {
                type: $('#edit-type').val(),
                category_id: $('#edit-category-id').val(),
                cms_page_id: $('#edit-cms-page-id').val(),
                url: $('#edit-url').val(),
                use_submenu_static_block: $('#edit-use-static-block:checked').val(),
                submenu_static_block_id: $('#edit-static-block').val(),
                use_subcategories_tree: $('#edit-use-subcategories-tree:checked').val()
            }

            this.currentNodeEdit().data = data;
            this.updateJsonData();
        },

        updateJsonData: function(){
            var data = $('.tree-container').jstree('get_json');
            $('[name="menu[json_data]"]').val(JSON.stringify(data));
            $('[name="menu[json_data]"]').trigger('change')
        }
    });
});
