<?php
namespace Magento\Catalog\Model\Category\Attribute\Source\Sortby;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Category\Attribute\Source\Sortby
 */
class Interceptor extends \Magento\Catalog\Model\Category\Attribute\Source\Sortby implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Config $catalogConfig)
    {
        $this->___init();
        parent::__construct($catalogConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllOptions');
        if (!$pluginInfo) {
            return parent::getAllOptions();
        } else {
            return $this->___callPlugins('getAllOptions', func_get_args(), $pluginInfo);
        }
    }
}
