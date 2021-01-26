<?php
namespace Itatiaia\EmailOrderProcessing\Setup;

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

        $table = $setup->getConnection()->newTable($setup->getTable('itatiaia_emailorderprocessing_email_flag'))
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
                        'order_increment_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        20,
                        ['nullable' => false],
                        'Order Increment ID'
                    )->addColumn(
                        'sent_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Email Sent at'
                    );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
