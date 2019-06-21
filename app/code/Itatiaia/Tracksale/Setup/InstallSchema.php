<?php


namespace Itatiaia\Tracksale\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $orderTable = 'sales_order';

        //Order table
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'nps_sent',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' =>'NPS Sent'
                ]
            );

        $setup->endSetup();
    }
}