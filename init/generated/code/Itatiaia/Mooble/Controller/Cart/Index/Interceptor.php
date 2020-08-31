<?php
namespace Itatiaia\Mooble\Controller\Cart\Index;

/**
 * Interceptor class for @see \Itatiaia\Mooble\Controller\Cart\Index
 */
class Interceptor extends \Itatiaia\Mooble\Controller\Cart\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\CartFactory $cartFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Psr\Log\LoggerInterface $logger, \Magento\Quote\Api\CartRepositoryInterface $cartRepository, \Magento\Framework\Data\Form\FormKey $formKey)
    {
        $this->___init();
        parent::__construct($context, $cartFactory, $productRepository, $logger, $cartRepository, $formKey);
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
