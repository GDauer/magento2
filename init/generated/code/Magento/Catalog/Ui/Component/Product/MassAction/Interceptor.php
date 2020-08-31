<?php
namespace Magento\Catalog\Ui\Component\Product\MassAction;

/**
 * Interceptor class for @see \Magento\Catalog\Ui\Component\Product\MassAction
 */
class Interceptor extends \Magento\Catalog\Ui\Component\Product\MassAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\AuthorizationInterface $authorization, \Magento\Framework\View\Element\UiComponent\ContextInterface $context, array $components = array(), array $data = array())
    {
        $this->___init();
        parent::__construct($authorization, $context, $components, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function isActionAllowed(string $actionType) : bool
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isActionAllowed');
        if (!$pluginInfo) {
            return parent::isActionAllowed($actionType);
        } else {
            return $this->___callPlugins('isActionAllowed', func_get_args(), $pluginInfo);
        }
    }
}
