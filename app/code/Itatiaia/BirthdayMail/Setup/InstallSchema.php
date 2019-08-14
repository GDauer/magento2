<?php


namespace Itatiaia\BirthdayMail\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('customer_entity'),
            'last_dob_mail',
            ['type' => Table::TYPE_DATE, 'nullable' => true, 'comment' => 'Data em que o ultimo email de aniversario foi enviado']
        );

        $setup->endSetup();
    }
}