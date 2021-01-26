<?php


namespace DigitalHub\CustomerAttributes\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{

    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'razao_social', [
            'type' => 'varchar',
            'label' => 'Razão Social',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'razao_social')
        ->addData(['used_in_forms' => [
                'adminhtml_customer',
                'adminhtml_checkout',
                'customer_account_create',
                'customer_account_edit'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'ie', [
            'type' => 'varchar',
            'label' => 'Inscrição Estadual',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'ie')
        ->addData(['used_in_forms' => [
                'adminhtml_customer',
                'adminhtml_checkout',
                'customer_account_create',
                'customer_account_edit'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'telephone', [
            'type' => 'varchar',
            'label' => 'Telefone',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'telephone')
        ->addData(['used_in_forms' => [
                'adminhtml_customer',
                'adminhtml_checkout',
                'customer_account_create',
                'customer_account_edit'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'nome_fantasia', [
            'type' => 'varchar',
            'label' => 'Nome Fantasia',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'nome_fantasia')
        ->addData(['used_in_forms' => [
                'adminhtml_customer',
                'adminhtml_checkout',
                'customer_account_create',
                'customer_account_edit'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'account_type', [
            'type' => 'varchar',
            'label' => 'Tipo de cadastro',
            'input' => 'select',
            'source' => 'DigitalHub\CustomerAttributes\Model\Customer\Attribute\Source\AccountType',
            'required' => true,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => '',
            'default' => 'pf'
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'account_type')
        ->addData(['used_in_forms' => [
                'adminhtml_customer',
                'adminhtml_checkout',
                'customer_account_create',
                'customer_account_edit'
            ]
        ]);
        $attribute->save();
    }
}
