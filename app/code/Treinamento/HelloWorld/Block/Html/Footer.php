<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Treinamento\HelloWorld\Block\Html;

use Magento\Framework\Phrase;
use Magento\Theme\Block\Html\Footer as MageFooter;

/**
 * Html page footer block
 *
 * @api
 * @since 100.0.2
 */
class Footer extends MageFooter
{
    /**
     * Retrieve copyright information
     *
     * @return Phrase
     */
    public function getCopyright(): Phrase
    {
        if (!$this->_copyright) {
            $this->_copyright = $this->_scopeConfig->getValue(
                'design/footer/copyright',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return __($this->_copyright . ' - Hello World');
    }
}
