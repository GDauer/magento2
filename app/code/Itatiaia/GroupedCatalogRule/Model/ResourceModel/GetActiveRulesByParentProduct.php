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
     * @param Product $product
     *
     * @return array
     */
    public function execute(Product $product): array
    {
        if($this->scopeConfig->getValue('sales/itatiaia_groupedcatalogrule/active')){

            $customerSession = $this->sessionFactory->create();

            $connection = $this->resourceConnection->getConnection();

            $catalogrule_table = $this->resourceConnection->getTableName('catalogrule');
            $catalogrule_product_table = $this->resourceConnection->getTableName('catalogrule_product');

            $customer_group_id = $customerSession->getCustomerGroupId();

            $sql = "select rule_product.*, rule.is_active as is_active from " . $catalogrule_product_table . " as rule_product,
             ".$catalogrule_table." as rule 
             where rule.rule_id = rule_product.rule_id
             and rule.is_active = 1
             and customer_group_id = ".$customer_group_id."
             and product_id = " . $product->getId() . "
             and from_time <= " . time() . "
             and (to_time = 0 OR to_time >= " . time() . ")
             group by rule_id
             order by sort_order ASC";

            $result = $connection->fetchAll($sql);

            if(count($result)){
                return $result;
            }
        }
        return [];
    }
}
