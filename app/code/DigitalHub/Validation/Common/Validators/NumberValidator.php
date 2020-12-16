<?php


namespace DigitalHub\Validation\Common\Validators;


class NumberValidator
{
    /**
     * @inheritDoc
     */
    public function isValid($value)
    {
        if (empty($value)) {
            return false;
        }

        if(strlen($value) > 10) {
            return false;
        }

        return true;
    }
}
