<?php


namespace DigitalHub\Validation\Common\Validators;


class NeighborhoodValidator
{
    /**
     * @inheritDoc
     */
    public function isValid($value)
    {
        if (empty($value)) {
            return false;
        }

        if(strlen($value) > 40) {
            return false;
        }

        return true;
    }
}
