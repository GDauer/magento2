<?php
namespace MundiPagg\MundiPagg\Controller\Customer\Remove;

/**
 * Interceptor class for @see \MundiPagg\MundiPagg\Controller\Customer\Remove
 */
class Interceptor extends \MundiPagg\MundiPagg\Controller\Customer\Remove implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Magento\Framework\View\Result\PageFactory $pageFactory, \MundiPagg\MundiPagg\Model\CardsRepository $cardsRepository, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\App\Request\Http $request, \MundiPagg\MundiPagg\Gateway\Transaction\Base\Config\Config $config, \MundiPagg\MundiPagg\Helper\Logger $logger)
    {
        $this->___init();
        parent::__construct($context, $jsonFactory, $pageFactory, $cardsRepository, $customerSession, $request, $config, $logger);
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
