<?php
namespace Magento\Sales\Block\Adminhtml\Order\Create\Form\Account;

/**
 * Interceptor class for @see \Magento\Sales\Block\Adminhtml\Order\Create\Form\Account
 */
class Interceptor extends \Magento\Sales\Block\Adminhtml\Order\Create\Form\Account implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Backend\Model\Session\Quote $sessionQuote, \Magento\Sales\Model\AdminOrder\Create $orderCreate, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Framework\Data\FormFactory $formFactory, \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor, \Magento\Customer\Model\Metadata\FormFactory $metadataFormFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $sessionQuote, $orderCreate, $priceCurrency, $formFactory, $dataObjectProcessor, $metadataFormFactory, $customerRepository, $extensibleDataObjectConverter, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toHtml');
        if (!$pluginInfo) {
            return parent::toHtml();
        } else {
            return $this->___callPlugins('toHtml', func_get_args(), $pluginInfo);
        }
    }
}
