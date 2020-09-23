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

use ArrayIterator;
use Itatiaia\GroupedCatalogRule\Helper\ApplyRules;
use Itatiaia\GroupedCatalogRule\Helper\Data;
use Itatiaia\GroupedCatalogRule\Model\ResourceModel\GetActiveRulesByParentProduct;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Helper\Context;
use Magento\CatalogRule\Helper\Data as HelperData;
use Magento\Framework\App\ResourceConnection;
use Magento\Customer\Model\SessionFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Registry;
use Magento\GroupedProduct\Model\Product\Type\GroupedFactory;
use Psr\Log\LoggerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

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
    private $productFactory;
    /** @var Registry */
    private $registry;
    /** @var GroupedFactory */
    private $groupedFactory;
    /** @var LoggerInterface */
    private $loggerInterface;
    /** @var StockRegistryInterface */
    private $stockRegistryInterface;
    /** @var Data */
    private $testClass;
    /** @var Product */
    private $product;
    /** @var StockItemInterface */
    private $stockItemInterface;
    /** @var Grouped */
    private $typeGrouped;
    /** @var Collection */
    private $productCollection;
    /** @var ObjectManager */
    private $objectManager;
    /** @var GetActiveRulesByParentProduct */
    private $getRules;
    /** @var AbstractType */
    private $abstractType;
    /** @var ApplyRules */
    private $applyRules;

    /**
     * @inheritdoc
     */
    public function setup(): void
    {
        $this->context = $this->createMock(Context::class);

        $this->helperData = $this->createMock(HelperData::class);

        $this->resourceConnection = $this->createMock(ResourceConnection::class);

        $this->sessionFactory = $this->getMockBuilder(SessionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productFactory = $this->getMockBuilder(ProductFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->registry = $this->createMock(Registry::class);

        $this->groupedFactory = $this->getMockBuilder(GroupedFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->loggerInterface = $this->createMock(LoggerInterface::class);

        $this->stockRegistryInterface = $this->createMock(StockRegistryInterface::class);

        $this->product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData', 'load', 'getId', 'getName', 'getTypeId', 'getCollection', 'getTypeInstance',
                'getExtensionAttributes', 'getSku', 'isAvailable', 'getQty', 'getFinalPrice', 'getPrice'])
            ->getMock();

        $this->stockItemInterface = $this->getMockBuilder(StockItemInterface::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->productCollection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getRules = $this->getMockBuilder(GetActiveRulesByParentProduct::class)
            ->setMethods(['execute'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->abstractType = $this->createMock(AbstractType::class);

        $this->applyRules = $this->getMockBuilder(ApplyRules::class)
            ->setMethods(['apply'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);

        $this->testClass = $this->objectManager->getObject(Data::class,
            [
                'context' => $this->context,
                'helperData' => $this->helperData,
                'resourceConnection' => $this->resourceConnection,
                'sessionFactory' => $this->sessionFactory,
                'productFactory' => $this->productFactory,
                'registry' => $this->registry,
                'groupedFactory' => $this->groupedFactory,
                'loggerInterface' => $this->loggerInterface,
                'stockRegistryInterface' => $this->stockRegistryInterface,
                'getActiveRulesByParentProduct' => $this->getRules
            ]);
    }

    public function testCalculateGroupedFinalPrice(): void
    {
        //Arrange
        $this->getRules
            ->expects($this->once())
            ->method('execute')
            ->with($this->product)
            ->willReturn([1]);

        $this->testGetRegularPriceIfRegistryExists();

        $this->applyRules
            ->expects($this->once())
            ->method('apply')
            ->with($this->getRules, 10);

        // Act
        $actualResult = $this->testClass->calculateGroupedFinalPrice($this->product);
        // Assert
        $this->assertNull($actualResult);
    }
    
    /**
     * Test getRegularPrice method if registry exists
     *
     * @return void
     */
    public function testGetRegularPriceIfRegistryExists(): void
    {
        // Arrange
        $productId = 10;

        $this->product
            ->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($productId);

        $key = 'grouped_regular_price_' . $productId;

        $this->registry
            ->expects($this->atLeastOnce())
            ->method('registry')
            ->with($key)
            ->willReturn(10);

        // Act
        $actualResult = $this->testClass->getRegularPrice($this->product);
        // Assert
        $this->assertEquals(10, $actualResult);
    }

    /**
     * Test getRegularPrice method if registry not exists
     *
     * @return void
     */
    public function testGetRegularPriceIfRegistryNotExistsAndProductIsGrouped(): void
    {
        // Arrange
        $productId = 1;

        $key = 'grouped_regular_price_' . $productId;

        $this->registry
            ->expects($this->once())
            ->method('registry')
            ->with($key)
            ->willReturn(null);

        $this->product
            ->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($productId);

        $this->product
            ->expects($this->once())
            ->method('getTypeId')
            ->willreturn('grouped');

        $this->product
            ->expects($this->once())
            ->method('getTypeInstance')
            ->willReturn($this->abstractType);

        $this->abstractType
            ->expects($this->once())
            ->method('getAssociatedProducts')
            ->with($this->product)
            ->willReturn(["1" => $this->product]);

        $this->product
            ->expects($this->atLeastOnce())
            ->method('getQty')
            ->willReturn(5);

        $this->product
            ->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($productId);

        $this->productFactory
            ->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->product);

        $childProduct = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPrice'])
            ->getMock();

        $this->product
            ->expects($this->once())
            ->method('load')
            ->willReturn($childProduct);

        $price = 10;

        $this->registry
            ->expects($this->once())
            ->method('register')
            ->with($key);

        $childProduct
            ->expects($this->once())
            ->method('getPrice')
            ->willReturn($price);

        // Act
        $actualResult = $this->testClass->getRegularPrice($this->product);
        // Assert
        $this->assertEquals(50, $actualResult);
    }

    /**
     * Test getRegularPrice method if registry not exists and product is not grouped
     *
     * @return void
     */
    public function testGetRegularPriceIfRegistryNotExistsAndProductIsNotGrouped(): void
    {
        // Arrange
        $key = 'grouped_regular_price_';

        $this->registry
            ->expects($this->once())
            ->method('registry')
            ->with($key)
            ->willReturn(null);

        $this->product
            ->expects($this->once())
            ->method('getTypeId')
            ->willreturn('simple');

        $price = 10;

        $this->product
            ->expects($this->once())
            ->method('getPrice')
            ->willReturn($price);

        $this->registry
            ->expects($this->once())
            ->method('register')
            ->with($key);

        // Act
        $actualResult = $this->testClass->getRegularPrice($this->product);
        // Assert
        $this->assertEquals(10, $actualResult);
    }

    /**
     * Test getPrice method if product type is grouped
     *
     * @return void
     */
    public function testGetPriceIfProductTypeIsGrouped(): void
    {
        // Arrange
        $this->product
            ->expects($this->once())
            ->method('getTypeId')
            ->willreturn('grouped');

        $this->product
            ->expects($this->once())
            ->method('getTypeInstance')
            ->willReturn($this->abstractType);

        $this->abstractType
            ->expects($this->once())
            ->method('getAssociatedProducts')
            ->with($this->product)
            ->willReturn(["1" => $this->product]);

        $this->product
            ->expects($this->atLeastOnce())
            ->method('getQty')
            ->willReturn(5);

        $productId = rand();

        $this->product
            ->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        $this->productFactory
            ->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->product);

        $childProduct = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFinalPrice'])
            ->getMock();

        $this->product
            ->expects($this->once())
            ->method('load')
            ->willReturn($childProduct);

        $childProduct
            ->expects($this->once())
            ->method('getFinalPrice')
            ->willReturn(10);

        // Act
        $actualResult = $this->testClass->getPrice($this->product);
        // Assert
        $this->assertEquals(50, $actualResult);
    }

    /**
     * Test getPrice method if product type is not grouped
     *
     * @return void
     */
    public function testGetPriceIfProductTypeIsNotGrouped(): void
    {
        // Arrange
        $this->product
            ->expects($this->once())
            ->method('getTypeId')
            ->willreturn('simple');

        $this->product
            ->expects($this->once())
            ->method('getPrice')
            ->willReturn(10);

        // Act
        $actualResult = $this->testClass->getPrice($this->product);
        // Assert
        $this->assertEquals(10, $actualResult);
    }


    /**
     * Data Provider updateGroupedStockStatusByStockItem
     *
     * @return array
     */
    public function updateGroupedStockStatusByStockItemDataProvider(): array
    {
        return [
            "ExpectGetParentIdWhenProductIsSimple" => [
                    'ProductTypeID' => 'simple',
                    'getParentIdsByChildCalls' => 1,
                    'updateChildrenProductByParentIdCalls' => 1
                ]
        ];
    }

    /**
     * Test updateGroupedStockStatusByStockItem method
     *
     * @param $productTypeID
     * @param $getParentIdsByChildCalls
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     *
     * @dataProvider updateGroupedStockStatusByStockItemDataProvider
     */
    public function testUpdateGroupedStockStatusByStockItem(
        $productTypeID,
        $getParentIdsByChildCalls
    ): void {

        // Arrange
        $groupedProductType = $this->createMock(Grouped::class);

        $this->groupedFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($groupedProductType);

        $this->loggerInterface
            ->expects($this->atLeastOnce())
            ->method('info');

        $this->stockItemInterface
            ->expects($this->once())
            ->method('getData');

        $this->stockItemInterface
            ->expects($this->once())
            ->method('getProductId');

        $this->productFactory
            ->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->product);

        $this->product
            ->expects($this->atLeastOnce())
            ->method('load')
            ->willReturnSelf();

        $productId = rand();

        $this->product
            ->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($productId);

        $this->product
            ->expects($this->once())
            ->method('getName')
            ->willReturn('abc');

        $this->product
            ->expects($this->once())
            ->method('getTypeId')
            ->willReturn($productTypeID);

        $updateChildrenProductByParentIdReturnal = [123];

        $groupedProductType
            ->expects($this->exactly($getParentIdsByChildCalls))
            ->method('getParentIdsByChild')
            ->with($productId)
            ->willReturn($updateChildrenProductByParentIdReturnal);

        $this->product
            ->expects($this->once())
            ->method('getCollection')
            ->willReturn($this->productCollection);

        $this->productCollection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->willReturnSelf();

        $this->productCollection
            ->expects($this->atLeastOnce())
            ->method('load')
            ->willReturnSelf();

        $groupProduct = [$this->product];

        $iterator = new ArrayIterator($groupProduct);

        $this->productCollection
            ->method('getIterator')
            ->willReturn($iterator);

        $this->product
            ->expects($this->once())
            ->method('getTypeInstance')
            ->willReturn($this->abstractType);

        $childrenIds = ['3' => [$productId]];

        $this->abstractType
            ->expects($this->once())
            ->method('getChildrenIds')
            ->willReturn($childrenIds);

        $this->product
            ->expects($this->once())
            ->method('getData')
            ->willReturn([]);

        $productExtensionInterface = $this->getMockBuilder(ProductExtensionInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStockItem'])
            ->getMock();

        $this->product
            ->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($productExtensionInterface);

        $productExtensionInterface
            ->expects($this->once())
            ->method('getStockItem')
            ->willReturn($this->stockItemInterface);

        $this->stockItemInterface
            ->expects($this->once())
            ->method('getIsInStock')
            ->willReturn(1);

        $this->updateStockProduct();

        // Act
        $actualResult = $this->testClass->updateGroupedStockStatusByStockItem($this->stockItemInterface);
        // Assert
        $this->assertNull($actualResult);

    }

    /**
     * Data Provider isGroupedProductInStock
     *
     * @return array
     */
    public function isGroupedProductInStockDataProvider(): array
    {
        return [
            'Product in Stock' => [
                'is_in_stock' => true
            ],
            'Product out of Stock' => [
                'is_in_stock' => false
            ]
        ];
    }


    /**
     * Test isGroupedProductInStock method
     *
     * @param $is_in_stock
     * @return void
     *
     * @dataProvider isGroupedProductInStockDataProvider
     */
    public function testIsGroupedProductInStock($is_in_stock): void
    {
        // Arrange
        $productId = rand();

        $this->product
            ->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        $childrenIds = ['3' => [$productId]];

        $this->abstractType
            ->expects($this->once())
            ->method('getChildrenIds')
            ->willReturn($childrenIds);

        $this->product
            ->expects($this->once())
            ->method('getTypeInstance')
            ->willReturn($this->abstractType);

        $this->productFactory
            ->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->product);

        $this->product
            ->expects($this->atLeastOnce())
            ->method('load')
            ->willReturnSelf();

        $this->product
            ->expects($this->once())
            ->method('isAvailable')
            ->willReturn($is_in_stock);

        // Act
        $actualResult = $this->testClass->isGroupedProductInStock($this->product);
        // Assert
        $this->assertEquals($actualResult, $is_in_stock);
    }
    
    /**
     * Test updateProductIsInStock method
     *
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testUpdateProductIsInStock(): void
    {
        // Arrange
        $this->updateStockProduct();

        // Act
        $actualResult = $this->testClass->updateProductIsInStock($this->product, true);
        // Assert
        $this->assertNull($actualResult);
    }

    /**
     * Test updateStockProduct method
     *
     * @return void
     */
    private function updateStockProduct(): void
    {
        $is_in_stock = true;

        $productSku = rand();

        $this->product
            ->expects($this->once())
            ->method('getSku')
            ->willReturn($productSku);

        $this->stockRegistryInterface
            ->expects($this->once())
            ->method('getStockItemBySku')
            ->with($productSku)
            ->willReturn($this->stockItemInterface);

        $this->stockItemInterface
            ->expects($this->once())
            ->method('setIsInStock')
            ->with($is_in_stock)
            ->willReturnSelf();

        $this->stockRegistryInterface
            ->expects($this->once())
            ->method('updateStockItemBySku')
            ->with($productSku, $this->stockItemInterface)
            ->willReturnSelf();
    }

    public function testUpdateChildrenProductByParentId(): void
    {
        $this->productFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->product);

        $abstractCollection = $this->createMock(AbstractCollection::class);

        $this->product
            ->expects($this->once())
            ->method('getCollection')
            ->willReturn($abstractCollection);

        $productId = rand();

        $abstractCollection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with('row_id', ['in' => $productId])
            ->willReturnSelf();

        // Act
        $actualResult = $this->testClass->updateChildrenProductByParentId($productId);
        // Assert
        $this->assertNull($actualResult);
    }
}
