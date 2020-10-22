<?php


namespace DigitalHub\NewsletterDate\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @inheritDoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $installer->getConnection()
            ->addColumn($setup->getTable('newsletter_subscriber'), 'registration_date', [
                'type' => Table::TYPE_TIMESTAMP,
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT,
                'comment'=> 'Data de cadastro'
            ]);
        $installer->endSetup();
    }
}