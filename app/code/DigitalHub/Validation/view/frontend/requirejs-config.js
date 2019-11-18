var config = {
    config: {
        mixins: {
            'Magento_Ui/js/lib/validation/validator': {
                'DigitalHub_Validation/js/checkoutTaxVatValidation': true
            }
        }
    },
    map: {
        "*": {
            customValidation: "DigitalHub_Validation/js/validation",
            taxVatValidation: "DigitalHub_Validation/js/inputTaxVatValidation"
        }
    }
};