<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 *
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 *
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Itatiaia\GroupedCatalogRule\Model\ResourceModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\ResourceConnection;

class GetActiveRulesByParentProduct
{

    /** @var ScopeConfigInterface */
    private $scopeConfig;
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var ResourceConnection */
    private $resourceConnection;
    /** @var Product */
    private $product;

    /**
     * GetActiveRulesByParentProduct constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param SessionFactory $sessionFactory
     * @param ResourceConnection $resourceConnection
     * @param Product $product
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SessionFactory $sessionFactory,
        ResourceConnection $resourceConnection,
        Product $product
    ) {
       $this->scopeConfig = $scopeConfig;
       $this->sessionFactory = $sessionFactory;
       $this->resourceConnection = $resourceConnection;
       $this->product = $product;
    }

    /**
     * execute method
     *
     * @param $product
     *
     * @return array
     */
    public function execute($product): array
    {
        if (!$product instanceof Product){
            return [];
        }

        $isActive = (bool) $this->scopeConfig->getValue('sales/itatiaia_groupedcatalogrule/active');

        if($isActive){

            $customerSession = $this->sessionFactory->create();
            $customerGroupId = $customerSession->getCustomerGroupId();

            $result = $this->getCatalogRules($product, $customerGroupId);

            if(count($result)){
                return $result;
            }
        }
        return [];
    }

    /**
     * getCatalogRules method
     *
     * @param Product $product
     * @param int $customerGroupId
     *
     * @return array
     */
    private function getCatalogRules(Product $product, $customerGroupId): array
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from(
                ['rule' =>  $this->resourceConnection->getTableName('catalogrule')],
                ['is_active' => 'rule.is_active']
            )->joinInner(
                ['rule_product' => $this->resourceConnection->getTableName('catalogrule_product')],
                'rule.rule_id = rule_product.rule_id'
            )
            ->where('rule.is_active = ?', 1)
            ->where('rule_product.customer_group_id = ?', $customerGroupId)
            ->where('rule_product.product_id = ?', $product->getId())
            ->where('rule_product.from_time <= ?', time())
            ->where('rule_product.to_time = 0 OR rule_product.to_time >= ?', time())
            ->order('rule_product.sort_order ASC')
            ->group(['rule.rule_id']);

        $select->setPart('disable_staging_preview', true);

        return $select->query()->fetchAll();
    }

}
