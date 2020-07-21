<?php


namespace DigitalHub\Validation\Common\Validators;


class StreetValidator
{
    /**
     * @inheritDoc
     */
    public function isValid($value)
    {
        if (empty($value)) {
            return false;
        }

        if(strlen($value) > 60) {
            return false;
        }

        return true;
    }
}
