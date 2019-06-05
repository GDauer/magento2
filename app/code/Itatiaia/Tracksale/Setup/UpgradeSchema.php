<?php


namespace Itatiaia\Tracksale\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            //Order table
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'nps_sent',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'comment' => 'NPS SENT'
                    ]
                );
        }

        $setup->endSetup();
    }
}