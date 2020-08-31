<?php
namespace Magento\Framework\DataObject\Copy;

/**
 * Interceptor class for @see \Magento\Framework\DataObject\Copy
 */
class Interceptor extends \Magento\Framework\DataObject\Copy implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\DataObject\Copy\Config $fieldsetConfig, \Magento\Framework\Api\ExtensionAttributesFactory $extensionAttributesFactory)
    {
        $this->___init();
        parent::__construct($eventManager, $fieldsetConfig, $extensionAttributesFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function copyFieldsetToTarget($fieldset, $aspect, $source, $target, $root = 'global')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'copyFieldsetToTarget');
        if (!$pluginInfo) {
            return parent::copyFieldsetToTarget($fieldset, $aspect, $source, $target, $root);
        } else {
            return $this->___callPlugins('copyFieldsetToTarget', func_get_args(), $pluginInfo);
        }
    }
}
