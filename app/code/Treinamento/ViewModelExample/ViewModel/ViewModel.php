<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Treinamento\ViewModelExample\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ViewModel implements ArgumentInterface
{
    /**
     * Returns something
     *
     * @return array
     */
    public function getSomething()
    {
        return [
            'Something' => __('It\'s something'),
            'Age' => 42
        ];
    }
}
