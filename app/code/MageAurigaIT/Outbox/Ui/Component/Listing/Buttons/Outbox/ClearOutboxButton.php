<?php

/**
 * Admin Logger extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Auriga License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.aurigait.com/magento_extensions/license.txt
 *
 * @category       MageAurigaIT
 * @package        MageAurigaIT_AdminLogger
 * @copyright      Copyright (c) 2017
 * @license        http://www.aurigait.com/magento_extensions/license.txt Auriga License
 */

namespace MageAurigaIT\Outbox\Ui\Component\Listing\Buttons\Outbox;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ClearLogButton
 */
class ClearOutboxButton implements ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    
    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
    }
    
    /**
     * @return array
     */
    public function getButtonData()
    {
        $base_url = 'ait_outbox/outbox/clear';
        $data = [
            'class' => 'primary',
            'sort_order' => 60,
            'label' => __('Clear outbox'),
            'on_click' => 'deleteConfirm(\'' . __(
                'Delete all sent emails?'
            ) . '\', \'' . $this->urlBuilder->getUrl($base_url) . '\')',
        ];
        return $data;
    }
}
