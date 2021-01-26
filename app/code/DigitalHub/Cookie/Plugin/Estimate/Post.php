<?php

namespace DigitalHub\Cookie\Plugin\Estimate;

use DigitalHub\Cookie\Model\Postcode;


class Post
{
    /** @var \DigitalHub\Cookie\Model\Postcode  */
    protected $postcodeCookie;

    public function __construct(
        Postcode $postcodeCookie
    ) {
        $this->postcodeCookie = $postcodeCookie;
    }

    public function beforeExecute(
        \DigitalHub\EstimateShipping\Controller\Estimate\Post $subject
    ) {
        $postcode = $subject->getRequest()->getPostValue('postcode');
        $oldPostcode = $this->postcodeCookie->get();

        // set if the postcode if exists, and is not the same that is already saved
        if (null !== $postcode && '' !== $postcode && $oldPostcode !== $postcode) {
            $this->postcodeCookie->set($postcode);
        }

        return [];
    }
}
