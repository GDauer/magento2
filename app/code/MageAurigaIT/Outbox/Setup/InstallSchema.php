<?php
namespace MageAurigaIT\Outbox\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mageaurigait_outbox_sent_emails')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('mageaurigait_outbox_sent_emails')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'E-mail ID'
            )->addColumn(
                'email_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'From email'
            )->addColumn(
                'email_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'To email'
            )->addColumn(
                'email_sub',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Email subject'
            )->addColumn(
                'email_body',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Email body'
            )->addColumn(
                'email_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false],
                'Whether sent successfully'
            )->addColumn(
                'email_raw',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Raw mail object'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Creation Time'
            )->setComment('Sent Email Table');
            
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('mageaurigait_outbox_sent_emails'),
                $setup->getIdxName(
                    $installer->getTable('mageaurigait_outbox_sent_emails'),
                    ['email_from', 'email_to', 'email_sub', 'email_body'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['email_from', 'email_to', 'email_sub', 'email_body'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
