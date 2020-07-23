<?php


namespace DigitalHub\Validation\Common\Validators;


interface ValidatorInterface
{
    /**
     * @param int|string|null $value
     * @return bool
     */
    public function isValid($value);
}
