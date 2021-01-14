<?php
namespace Itatiaia\SitemapXml\Setup;

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
        $table = $setup->getConnection()->newTable($setup->getTable('itatiaia_sitemapxml_sitemap'))
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
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'Sitemap Name'
                    )->addColumn(
                        'filename',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'Sitemap File Name'
                    )->addColumn(
                        'type',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        45,
                        ['nullable' => false],
                        'Sitemap File Type'
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
                        'Sitemap Status Code'
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
