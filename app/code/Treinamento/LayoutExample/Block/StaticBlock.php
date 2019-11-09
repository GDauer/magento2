<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Treinamento\LayoutExample\Block;

use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;

class StaticBlock extends Template
{
    /**
     * Returns static string
     *
     * @return Phrase
     */
    public function getStaticString()
    {
        return __('Static String');
    }
}
