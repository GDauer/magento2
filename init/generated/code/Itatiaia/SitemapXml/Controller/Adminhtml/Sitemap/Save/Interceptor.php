<?php
namespace Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap\Save;

/**
 * Interceptor class for @see \Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap\Save
 */
class Interceptor extends \Itatiaia\SitemapXml\Controller\Adminhtml\Sitemap\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\ResultFactory $resultFactory, \Itatiaia\SitemapXml\Model\SitemapFactory $sitemapFactory)
    {
        $this->___init();
        parent::__construct($context, $resultFactory, $sitemapFactory);
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
