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

use Itatiaia\GroupedCatalogRule\Helper\ApplyRules;
use PHPUnit\Framework\TestCase;
use Magento\CatalogRule\Helper\Data;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class ApplyRulesTest extends TestCase
{
    private $data;
    /** @var Data */
    private $testClass;

    public function setup():void
    {
        $this->data = $this->createMock(Data::class);

        $objectManager = new ObjectManager($this);

        $this->testClass = $objectManager->getObject(ApplyRules::class
            ,[
                'data' => $this->data
            ]);
    }

    public function ApplyDataProvider(): array
    {
        return [
            'HasRules' => [
                'rules' => [
                    0 => [
                        'action_operator' => 'by_percent',
                        'action_amount' => 14.8180,
                        'action_stop' => 1
                    ],
                ],
                'finalPrice' => 1000,
                'calcPriceRuleCalls' => 1,
                'expectedResult' => 900
            ],

            'HasTwoRules' => [
                'rules' => [
                    0 => [
                        'action_operator' => 'by_fixed',
                        'action_amount' => 100,
                        'action_stop' => 1
                    ],
                    1 => [
                        'action_operator' => 'by_percent',
                        'action_amount' => 14.8180,
                        'action_stop' => 1
                    ],
                ],
                'finalPrice' => 1000,
                'calcPriceRuleCalls' => 1,
                'expectedResult' => 900
            ],

            'NoRules' => [
                'rules' => null,
                'finalPrice' => 1000,
                'calcPriceRuleCalls' => 0,
                'expectedResult' => 1000
            ],
        ];
    }

    /**
     * teste apply method
     *
     * @param $rules
     * @param $finalPrice
     * @param $calcPriceRuleCalls
     * @param $expectedResult
     *
     * @dataProvider ApplyDataProvider
     */
    public function testApply(
        $rules,
        $finalPrice,
        $calcPriceRuleCalls,
        $expectedResult
    ): void {

        // Arrange
        $this->data
            ->expects($this->exactly($calcPriceRuleCalls))
            ->method("calcPriceRule")
            ->willReturn($expectedResult);

        // Act
        $actualResult = $this->testClass->apply($rules,$finalPrice);

        // Assert
        $this->assertEquals($actualResult,$expectedResult);
    }
}
