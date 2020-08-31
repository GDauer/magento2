<?php
namespace DigitalHub\FeedManager\Helper\Feed;

/**
 * Interceptor class for @see \DigitalHub\FeedManager\Helper\Feed
 */
class Interceptor extends \DigitalHub\FeedManager\Helper\Feed implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context)
    {
        $this->___init();
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function parseTemplateVariables($collection)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'parseTemplateVariables');
        if (!$pluginInfo) {
            return parent::parseTemplateVariables($collection);
        } else {
            return $this->___callPlugins('parseTemplateVariables', func_get_args(), $pluginInfo);
        }
    }
}
