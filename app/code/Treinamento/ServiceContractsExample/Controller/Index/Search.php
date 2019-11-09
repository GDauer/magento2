<?php

declare(strict_types=1);

namespace Treinamento\ServiceContractsExample\Controller\Index;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Search
 *
 * @package Treinamento\ServiceContractsExample\Controller\Index
 */
class Search extends Action implements HttpGetActionInterface
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteria;

    /** @var FilterBuilder */
    private $filter;

    /**
     * Search constructor.
     *
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteria
     * @param FilterBuilder $filter
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteria,
        FilterBuilder $filter
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteria = $searchCriteria;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $filterCreated = $this->filter
            ->setField(ProductInterface::NAME)
            ->setConditionType('like')
            ->setValue('%hoodie%')
            ->create();

        $this->searchCriteria->addFilters([$filterCreated]);
        $this->searchCriteria->setPageSize(20);
        $criteria = $this->searchCriteria->create();

        $products = $this->productRepository->getList($criteria)->getItems();
        $result = $this->setResult($products);

        return $resultPage->setData($result);
    }

    /**
     * @param ProductInterface[] $products
     * @return array|void
     */
    private function setResult($products)
    {
        if (!empty($products)) {
            foreach ($products as $items) {
                $result[] = [$items->getName(), $items->getSku()];
            }
            return $result;
        }
    }
}
