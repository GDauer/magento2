<?php

namespace DigitalHub\WishlistGrid\Block\Adminhtml\Report\Customer\Wishlist;


class ReportGrid extends \Magento\Backend\Block\Widget\Grid
{
    /**
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection \Magento\MultipleWishlist\Model\ResourceModel\Item\Report\Collection */
        $collection = $this->getCollection();

        /** @var \Magento\Framework\DB\Adapter\AdapterInterface $connection */
        $connection = $collection->getConnection();

        $collection->filterByStoreIds($this->_getStoreIds());
        $collection->getSelect()->joinLeft(
            ['customer_entity' => $connection->getTableName('customer_entity')],
            'wishlist_table.customer_id = customer_entity.entity_id',
            ['customer_email' => 'email', 'customer_group_id' => 'group_id']
        )->joinLeft(
            ['cpip' => $connection->getTableName('catalog_product_index_price')],
            'main_table.product_id = cpip.entity_id AND customer_entity.group_id = cpip.customer_group_id',
            ['price' => 'max_price']
        );

        return parent::_prepareCollection();
    }

    /**
     * Get allowed store ids array intersected with selected scope in store switcher
     *
     * @return  array
     */
    protected function _getStoreIds()
    {
        $storeIdsStr = $this->getRequest()->getParam('store_ids');
        $allowedStoreIds = array_keys($this->_storeManager->getStores());
        if (strlen($storeIdsStr)) {
            $storeIds = explode(',', $storeIdsStr);
            $storeIds = array_intersect($allowedStoreIds, $storeIds);
        } else {
            $storeIds = $allowedStoreIds;
        }
        $storeIds = array_values($storeIds);
        return $storeIds;
    }
}
