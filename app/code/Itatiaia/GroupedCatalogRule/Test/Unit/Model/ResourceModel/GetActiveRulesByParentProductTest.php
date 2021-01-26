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

namespace Itatiaia\GroupedCatalogRule\Test\Unit\Model\ResourceModel;

use Itatiaia\GroupedCatalogRule\Model\ResourceModel\GetActiveRulesByParentProduct;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class GetActiveRulesByParentProductTest extends TestCase
{
    /** @var Product */
    private $product;
    /** @var GetActiveRulesByParentProduct */
    private $testClass;
    /** @var ObjectManager */
    private $objectManager;
    /** @var ScopeConfigInterface */
    private $scopeConfigInterface;
    /** @var SessionFactory */
    private $sessionFactory;
    /** @var ResourceConnection */
    private $resourceConnection;
    /** @var AdapterInterface */
    private $adapterInterface;
    /** @var Session */
    private $session;

    /**
     * @inheritdoc
     */
    public function setup(): void
    {
        $this->product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getValue', 'isSetFlag'])
            ->getMock();

        $this->sessionFactory = $this->getMockBuilder(SessionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'getCustomerGroupId'])
            ->getMock();

        $this->resourceConnection = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'getTableName'])
            ->getMock();

        $this->adapterInterface = $this->createMock(AdapterInterface::class);

        $this->session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCustomerGroupId'])
            ->getMock();

        $this->objectManager = new ObjectManager($this);

        $this->testClass = $this->objectManager->getObject(GetActiveRulesByParentProduct::class,
            [
                'product' => $this->product,
                'scopeConfig' => $this->scopeConfigInterface,
                'sessionFactory' => $this->sessionFactory,
                'resourceConnection' => $this->resourceConnection,
                'adapterInterface' => $this->adapterInterface,
                'session' => $this->session
            ]);
    }

    /**
     * Test execute method if rules are active and found results
     *
     * @return void
     */
    public function testExecuteIfRulesAreActiveAndFoundResults(): void
    {
        //Arrange
        $path = 'sales/itatiaia_groupedcatalogrule/active';

        $this->scopeConfigInterface
            ->expects($this->once())
            ->method('getValue')
            ->with($path)
            ->willReturn(true);

        $this->sessionFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->session);

        $this->resourceConnection
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->adapterInterface);

        $this->resourceConnection
            ->expects($this->exactly(2))
            ->method('getTableName')
            ->withConsecutive(['catalogrule'], ['catalogrule_product'])
            ->willReturn('catalogrule', 'catalogrule_product');

        $customerGroupId = rand();

        $this->session
            ->expects($this->once())
            ->method('getCustomerGroupId')
            ->willReturn($customerGroupId);

        $productId = rand();

        $this->product
            ->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        $results = [1];

        $this->adapterInterface
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($results);

        //Act
        $actualResult = $this->testClass->execute($this->product);

        //Assert
        $this->assertEquals($results,$actualResult);

    }

    /**
     * Test execute method if rules are active and not found results
     *
     * @return void
     */
    public function testExecuteIfRulesAreActiveAndNotFoundResults(): void
    {
        //Arrange
        $path = 'sales/itatiaia_groupedcatalogrule/active';

        $this->scopeConfigInterface
            ->expects($this->once())
            ->method('getValue')
            ->with($path)
            ->willReturn(true);

        $this->sessionFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->session);

        $this->resourceConnection
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->adapterInterface);

        $this->resourceConnection
            ->expects($this->exactly(2))
            ->method('getTableName')
            ->withConsecutive(['catalogrule'], ['catalogrule_product'])
            ->willReturn('catalogrule', 'catalogrule_product');

        $customerGroupId = rand();

        $this->session
            ->expects($this->once())
            ->method('getCustomerGroupId')
            ->willReturn($customerGroupId);

        $productId = rand();

        $this->product
            ->expects($this->once())
            ->method('getId')
            ->willReturn($productId);

        $results = [];

        $this->adapterInterface
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($results);

        //Act
        $actualResult = $this->testClass->execute($this->product);

        //Assert
        $this->assertEquals($results,$actualResult);
    }

    /**
     * Test execute method if rules not active
     *
     * @return void
     */
    public function testExecuteIfRulesAreNotActive(): void
    {
        //Arrange
        $path = 'sales/itatiaia_groupedcatalogrule/active';

        $this->scopeConfigInterface
            ->expects($this->once())
            ->method('getValue')
            ->with($path)
            ->willReturn('');

        //Act
        $actualResult = $this->testClass->execute($this->product);

        //Assert
        $this->assertEquals([],$actualResult);
    }
}
