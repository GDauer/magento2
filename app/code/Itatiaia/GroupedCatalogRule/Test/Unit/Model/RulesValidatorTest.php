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

namespace Itatiaia\GroupedCatalogRule\Test\Unit\Model;

use Itatiaia\GroupedCatalogRule\Model\RulesValidator;
use Magento\TestFramework\Indexer\TestCase;
use Magento\CatalogRule\Helper\Data;
use Itatiaia\GroupedCatalogRule\Model\RulesValidator as TestClass;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class RulesValidatorTest extends TestCase
{
    private $data;
    private $testClass;

    public function setup():void
    {
        $this->data = $this->createMock(Data::class);

        $objectManager = new ObjectManager($this);

        $this->testClass = $objectManager->getObject(RulesValidator::class
            ,[
                'data' => $this->data
            ]);
    }

    public function testValidate()
    {
        $this->data->expects($this->once())
            ->method("calcPriceRule")
            ->with()
            ->willReturn();
    }
}
