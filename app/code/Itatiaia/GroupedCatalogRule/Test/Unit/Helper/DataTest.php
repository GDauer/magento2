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

namespace Itatiaia\GroupedCatalogRule\Test\Unit\Helper;

use Itatiaia\GroupedCatalogRule\Helper\Data;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Helper\Context;
use Magento\CatalogRule\Helper\Data as HelperData;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Model\SessionFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\GroupedProduct\Model\Product\Type\GroupedFactory;
use Psr\Log\LoggerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Model\Product;

class DataTest extends TestCase
{
    /** @var Context */
    private $context;
    /** @var HelperData */
    private $helperData;
    /** @var ResourceConnection */
    private $resourceConnection;
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var ProductFactory */
    private $productfactory;
    /** @var Registry */
    private $registry;
    /** @var GroupedFactory */
    private $groupedFactory;
    /** @var LoggerInterface */
    private $loggerInterface;
    /** @var StockRegistryInterface */
    private $stockRegistryInterface;
    /** @var TestClass */
    private $testClass;

    public function setup(): void
    {
        $this->context = $this->createMock(Context::class);

        $this->helperData = $this->createMock(HelperData::class);

        $this->resourceConnection = $this->createMock(ResourceConnection::class);

        $this->sessionFactory = $this->createMock(SessionFactory::class);

        $this->productfactory = $this->createMock(ProductFactory::class);

        $this->registry = $this->createMock(Registry::class);

        $this->groupedFactory = $this->createMock(GroupedFactory::class);

        $this->loggerInterface = $this->createMock(LoggerInterface::class);

        $this->stockRegistryInterface = $this->createMock(StockRegistryInterface::class);

        $objectManager = new ObjectManager($this);

        $this->testClass = $objectManager->getObject(Data::class,
            [
                'context' => $this->context,
                'helperData' => $this->helperData,
                'resourceConnection' => $this->resourceConnection,
                'sessionFactory' => $this->sessionFactory,
                'productFactory' => $this->productfactory,
                'registry' => $this->registry,
                'groupedFactory' => $this->groupedFactory,
                'loggerInterface' => $this->loggerInterface,
                'stockRegistryInterface' => $this->stockRegistryInterface
            ]);
    }

    public function calculateGroupedFinalPrice()
    {
        $product = $this->createMock(Product::class);
    }
}
