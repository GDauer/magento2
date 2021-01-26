<?php

namespace DigitalHub\SalesGrid\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Backend\Model\UrlInterface;

class Products extends Column
{
    protected $orderRepository;
    protected $backendUrlInterface;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderRepositoryInterface $orderRepository,
        UrlInterface $backendUrlInterface,
        array $components = [],
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->backendUrlInterface = $backendUrlInterface;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$items) {
                if (!isset($items['products']) || !isset($items['products_id'])) {
                    continue;
                }

                $products = explode(',', $items['products']);
                $productsIds = explode(',', $items['products_id']);

                $products = array_combine($productsIds, $products);
                $productArr = [];
                foreach ($products as $pid => $sku) {
                    $productArr[] = '<a href="' . $this->backendUrlInterface->getUrl(
                        'catalog/product/edit',
                            ['id' => $pid]
                        ) .'">' . $sku . '</a>' ;
                }
                $items['products'] = implode('<br />', $productArr);
                unset($productArr);
            }
        }
        return $dataSource;
    }
}