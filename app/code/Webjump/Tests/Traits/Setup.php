<?php

namespace Webjump\Tests\Traits
{
    use Magento\Framework\DB\Adapter\AdapterInterface;
    use Magento\Framework\Setup\SchemaSetupInterface;
    use Magento\Framework\Setup\ModuleContextInterface;

    trait Setup
    {
        protected function addColumn(string $version, int $index = 0)
        {
            $counter = 0;
            $checkTable[] = null;
            $checkColumn[] = null;
            $checkParams[] = null;

            $mockAdapter = $this
                ->getMockBuilder(AdapterInterface::class)
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

            $mockAdapter
                ->expects($this->any())
                ->method('addColumn')
                ->will($this->returnCallback(function ($table, $column, $params) use (&$checkTable, &$checkColumn, &$checkParams, &$counter) {
                    $checkTable[$counter] = $table;
                    $checkColumn[$counter] = $column;
                    $checkParams[$counter] = $params;
                    ++$counter;
                }));

            $mockSetup = $this
                ->getMockBuilder(SchemaSetupInterface::class)
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

            $mockSetup
                ->expects($this->any())
                ->method('getConnection')
                ->will($this->returnValue($mockAdapter));

            $mockSetup
                ->expects($this->any())
                ->method('getTable')
                ->will($this->returnCallback(function ($table) {
                    return $table;
                }));

            $mockContext = $this
                ->getMockBuilder(ModuleContextInterface::class)
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();

            $mockContext
                ->expects($this->any())
                ->method('getVersion')
                ->will($this->returnValue($version));

            $this->instance->upgrade($mockSetup, $mockContext);

            return [
                'table' => $checkTable[$index],
                'column' => $checkColumn[$index],
                'params' => $checkParams[$index],
            ];
        }
    }
}
