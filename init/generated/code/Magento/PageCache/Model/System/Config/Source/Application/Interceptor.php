<?php
namespace Magento\PageCache\Model\System\Config\Source\Application;

/**
 * Interceptor class for @see \Magento\PageCache\Model\System\Config\Source\Application
 */
class Interceptor extends \Magento\PageCache\Model\System\Config\Source\Application implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toOptionArray');
        if (!$pluginInfo) {
            return parent::toOptionArray();
        } else {
            return $this->___callPlugins('toOptionArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray();
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }
}
