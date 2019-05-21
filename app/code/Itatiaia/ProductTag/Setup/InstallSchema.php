<?php

namespace Itatiaia\ProductTag\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table = $setup->getConnection()->newTable($setup->getTable('itatiaia_product_tag'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'Entity ID'
            )->addColumn(
                'tag_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Tag Name'
            )->addColumn(
                'image',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Image'
            )->addColumn(
                'position',
                Table::TYPE_INTEGER,
                255,
                ['nullable' => false],
                'Position'
            )->addColumn(
                'is_active',
                Table::TYPE_TEXT,
                null,
                [],
                'Is Active'
            );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }

}
