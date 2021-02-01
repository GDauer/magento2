<?php
namespace DigitalHub\MenuManager\Setup;

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
        $table = $setup->getConnection()->newTable($setup->getTable('digitalhub_menumanager_menu'))
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
                        'Menu Name'
                    )->addColumn(
                        'identifier',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'Menu Identifier'
                    )->addColumn(
                        'type',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        45,
                        ['nullable' => false, 'default' => 'horizontal'],
                        'Menu type'
                    )->addColumn(
                        'active',
                        \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        [
                          'nullable' => false,'default' => '1',
                        ],
                        'Is Active'
                    )->addColumn(
                        'json_data',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Menu JSON data'
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
    }
}
