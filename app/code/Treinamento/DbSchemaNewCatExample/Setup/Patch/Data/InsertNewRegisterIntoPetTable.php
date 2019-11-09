<?php

declare(strict_types=1);

namespace Treinamento\DbSchemaNewCatExample\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class InsertNewRegisterIntoPetTable
 *
 * @package Magento\Catalog\Setup\Patch\Data
 */
class InsertNewRegisterIntoPetTable implements DataPatchInterface
{
    private const INITIAL_VALUES = [
        'customer_id' => 1,
        'pet_name'    => 'Sarabi',
        'pet_type'    => 'Cat'
    ];

    private const TABLE_NAME = 'pet_table';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * ChangePriceAttributeDefaultScope constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritDoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->insertMultiple(self::TABLE_NAME, self::INITIAL_VALUES);
    }

    /**
     * {@inheritDoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAliases()
    {
        return [];
    }
}
