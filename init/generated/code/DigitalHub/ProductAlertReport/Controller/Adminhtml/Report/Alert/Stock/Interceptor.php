<?php
namespace DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\Alert\Stock;

/**
 * Interceptor class for @see \DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\Alert\Stock
 */
class Interceptor extends \DigitalHub\ProductAlertReport\Controller\Adminhtml\Report\Alert\Stock implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $dateFilter, $timezone);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
