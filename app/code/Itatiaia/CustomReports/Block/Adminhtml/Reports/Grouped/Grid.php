<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Itatiaia\CustomReports\Block\Adminhtml\Reports\Grouped;

/**
 * Adminhtml low stock products report grid block
 *
 * @api
 * @author      Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class Grid extends \Magento\Backend\Block\Widget\Grid
{
    private $_groupedCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Itatiaia\CustomReports\Model\ResourceModel\Report\GroupedCollection $groupedCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Itatiaia\CustomReports\Model\ResourceModel\Report\GroupedCollectionFactory $groupedCollectionFactory,
        array $data = []
    ) {
        $this->_groupedCollectionFactory = $groupedCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $website = $this->getRequest()->getParam('website');
        $group = $this->getRequest()->getParam('group');
        $store = $this->getRequest()->getParam('store');

        if ($website) {
            $storeIds = $this->_storeManager->getWebsite($website)->getStoreIds();
            $storeId = array_pop($storeIds);
        } elseif ($group) {
            $storeIds = $this->_storeManager->getGroup($group)->getStoreIds();
            $storeId = array_pop($storeIds);
        } elseif ($store) {
            $storeId = (int)$store;
        } else {
            $storeId = null;
        }

        /** @var $collection \Magento\Reports\Model\ResourceModel\Product\Lowstock\Collection  */
        $collection = $this->_groupedCollectionFactory->create();

        if ($storeId) {
            $collection->setStoreIds($storeId);
        }


        // print_r((string)$collection->getSelect());
        // exit;

        $this->setCollection($collection);


        return parent::_prepareCollection();
    }
}
