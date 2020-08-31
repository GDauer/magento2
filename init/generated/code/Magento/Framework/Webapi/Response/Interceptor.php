<?php
namespace Magento\Framework\Webapi\Response;

/**
 * Interceptor class for @see \Magento\Framework\Webapi\Response
 */
class Interceptor extends \Magento\Framework\Webapi\Response implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader($name, $value, $replace = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHeader');
        if (!$pluginInfo) {
            return parent::setHeader($name, $value, $replace);
        } else {
            return $this->___callPlugins('setHeader', func_get_args(), $pluginInfo);
        }
    }
}
