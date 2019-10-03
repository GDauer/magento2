define(
	[
		'jquery',
		'Magento_Ui/js/model/messageList',
		'mage/translate'
	],
	function ($, messageList,$t) {
		'use strict';
		return {
			validationErrors: [],

			/**
			 * CPF validation
			 *
			 * @param {string} cpf CPF to be validated
			 * @returns {boolean}
			 */
			validaCPF: function(cpf) {
				cpf = cpf.replace(/[\.-]+/g, "");
				let num, dig, sum, i, res, eqDigits;
				eqDigits = 1;
				if (cpf.length < 11)
					return false;
				for (i = 0; i < cpf.length - 1; i++) {
					if (cpf.charAt(i) != cpf.charAt(i + 1)) {
						eqDigits = 0;
						break;
					}
				}
				if (!eqDigits) {
					num = cpf.substring(0, 9);
					dig = cpf.substring(9);
					sum = 0;
					for (i = 10; i > 1; i--) {
						sum += num.charAt(10 - i) * i;
					}
					res = sum % 11 < 2 ? 0 : 11 - sum % 11;
					if (res != dig.charAt(0)) {
						return false;
					}
					num = cpf.substring(0, 10);
					sum = 0;
					for (i = 11; i > 1; i--) {
						sum += num.charAt(11 - i) * i;
					}
					res = sum % 11 < 2 ? 0 : 11 - sum % 11;
					return res == dig.charAt(1);
				}
				return false;
			},

			validate: function() {
				var vatIdField = $('input[name=vat_id] :visible');
				if (vatIdField.length <= 0) {
					vatIdField = $('input[name=taxvat] :visible');
				}
				if (vatIdField.length <= 0 || $('.shipping-address-item').length > 0) {
					return true;
				}
				var val = vatIdField.val();

				this.validationErrors = [];

				if (undefined === val || '' === val) {
					messageList.addErrorMessage({
						message: $t('O campo ') + 'CPF' + $t(' é obrigatório.')
					});

					return false;
				}
				if (!this.validaCPF(val)) {
					messageList.addErrorMessage({
						message: 'CPF' + $t(' inválido.')
					});

					return false;
				}

				return true;
			}
		}
	}
);
