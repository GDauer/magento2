<?php
namespace Itatiaia\KondutoCustom\Plugin\Ui\Component;

use Swissup\CheckoutFields\Model\ResourceModel\Field\Collection;
use Konduto\Antifraud\Helper\Data;

class OrderListingData
{
    /**
     * Checkout fields collection
     * @var Collection
     */
    protected $fieldsCollection;

    /**
     * Checkout fields helper
     * @var \Konduto\Antifraud\Helper\Data
     */
    protected $helper;

    /**
     * @param Collection $fieldsCollection
     * @param \Konduto\Antifraud\Helper\Data $helper
     */
    public function __construct(
        Collection $fieldsCollection,
        Data $helper
    ) {
        $this->fieldsCollection = $fieldsCollection;
        $this->helper = $helper;
    }

    /**
     * @param  \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting $subject
     * @param  \Magento\Framework\Api\Search\SearchResultInterface $result
     * @return \Magento\Framework\Api\Search\SearchResultInterface
     */
 /*   public function afterSearch(
        \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting $subject,
        \Magento\Framework\Api\Search\SearchResultInterface $result
    ) {
        $connection = $result->getConnection();
        if ($this->helper->isEnabled() &&
            $result->getMainTable() === $connection->getTableName('sales_order_grid')
        ) {
            $select = $result->getSelect();
            $select->joinLeft(
                ['kondutohist' => $connection->getTableName('konduto_antifraud_history')],
                'main_table.entity_id = kondutohist.order_id',
                ['konduto_status' => 'status']
            )->group('kondutohist.status')
            ->columns('status');

            // Fix column in where clause is ambiguous error
            $where = $select->getPart('where');
            foreach ($where as &$item) {
                if (strpos($item, '(`updated_at`') !== false) {
                    $item = str_replace('`updated_at`', '`main_table`.`updated_at`', $item);
                }

                if (strpos($item, '(`created_at`') !== false) {
                    $item = str_replace('`created_at`', '`main_table`.`created_at`', $item);
                }
            }
            $select->setPart('where', $where);
        }

        return $result;
    }*/
}
