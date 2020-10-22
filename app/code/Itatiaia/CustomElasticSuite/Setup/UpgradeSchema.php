<?php


namespace Itatiaia\CustomElasticSuite\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 * @package Itatiaia\CustomElasticSuite\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrade
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $connection = $setup->getConnection();
            $table = $setup->getTable('catalog_eav_attribute');

            $connection->addColumn(
                $table,
                'es_ignore_index_on_parent',
                [
                    'type' => Table::TYPE_BOOLEAN,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Ignore indexing child attributes on parent product',
                ]
            );
        }

        $setup->endSetup();
    }
}