<?php
namespace DEG\CustomReports\Controller\Adminhtml\CustomReport\ExportCsv;

/**
 * Interceptor class for @see \DEG\CustomReports\Controller\Adminhtml\CustomReport\ExportCsv
 */
class Interceptor extends \DEG\CustomReports\Controller\Adminhtml\CustomReport\ExportCsv implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \DEG\CustomReports\Controller\Adminhtml\CustomReport\Builder $builder)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $builder);
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
