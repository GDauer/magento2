<?php
namespace DigitalHub\FeedManager\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $table = $setup->getConnection()->newTable($setup->getTable('digitalhub_feedmanager_feed_template'))
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                          'identity' => true,
                          'nullable' => false,
                          'primary' => true,
                          'unsigned' => true,
                        ],
                        'Entity ID'
                    )->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'Template Name'
                    )->addColumn(
                        'code',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Template Code'
                    )->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Created at'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Updated at'
                    );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable($setup->getTable('digitalhub_feedmanager_feed'))
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                          'identity' => true,
                          'nullable' => false,
                          'primary' => true,
                          'unsigned' => true,
                        ],
                        'Entity ID'
                    )->addColumn(
                        'store_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'Store Id'
                    )->addColumn(
                        'template_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'Template Id'
                    )->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'Feed Name'
                    )->addColumn(
                        'filename',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'Feed File Name'
                    )->addColumn(
                        'active',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        NULL,
                        ['nullable' => false],
                        'Active'
                    )->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        45,
                        ['nullable' => false],
                        'Feed Status Code'
                    )->addColumn(
                        'conditions',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Conditions JSON Object'
                    )->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Created at'
                    )->addColumn(
                        'generated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Generated at'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Updated at'
                    );

        $setup->getConnection()->createTable($table);
    }
}
