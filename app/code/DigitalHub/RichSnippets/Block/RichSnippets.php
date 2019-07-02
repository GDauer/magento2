<?php

namespace DigitalHub\RichSnippets\Block;

use Magento\Framework\View\Element\Template;

class RichSnippets extends Template
{
    public function __construct
    (
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }
}