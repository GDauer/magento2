<?php


namespace Itatiaia\ImportRules\Model\Import\CatalogRule;


interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
    const ERROR_INVALID_ID = 'EmptyID';
    const ERROR_DISCOUNT_AMOUNT_IS_EMPTY = 'EmptyTITLE';
    /**
     * Initialize validator
     *
     * @return $this
     */
    public function init($context);
}