define([
  'Magento_Ui/js/grid/columns/column',
  'jquery',
  'mage/template',
  'text!MageAurigaIT_Outbox/templates/sent_emails/view_email.html',
  'Magento_Ui/js/modal/modal'
  ], function (Column, $, mageTemplate, viewEmailTemplate) {
    'use strict';
    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        getLabel: function (row) {
            return row[this.index + '_html']
        },
        getResendButtonLabel: function (row) {
            return row[this.index + '_resendbuttonlabel']
        },
        getEmailBody: function (row) {
            return row[this.index + '_body']
        },
        getResendMailURL: function (row) {
            return row[this.index + '_resendmailurl']
        },
        viewEmail: function (row) {
            var modalHtml = viewEmailTemplate.replace("{{EMAIL_BODY}}", this.getEmailBody(row));
            modalHtml = mageTemplate(modalHtml, {
                resendbuttonlabel: this.getResendButtonLabel(row),
                resendmailurl: this.getResendMailURL(row)
            });
            var previewPopup = $('<div/>').html(modalHtml);
            previewPopup.modal({
                innerScroll: true,
                modalClass: '',
                buttons: []
            }).trigger('openModal');
        },
        getFieldHandler: function (row) {
            return this.viewEmail.bind(this, row);
        }
    });
});
