<?php
namespace Magento\Framework\View\Asset\Source;

/**
 * Interceptor class for @see \Magento\Framework\View\Asset\Source
 */
class Interceptor extends \Magento\Framework\View\Asset\Source implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Filesystem $filesystem, \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory, \Magento\Framework\View\Asset\PreProcessor\Pool $preProcessorPool, \Magento\Framework\View\Design\FileResolution\Fallback\StaticFile $fallback, \Magento\Framework\View\Design\Theme\ListInterface $themeList, \Magento\Framework\View\Asset\PreProcessor\ChainFactoryInterface $chainFactory)
    {
        $this->___init();
        parent::__construct($filesystem, $readFactory, $preProcessorPool, $fallback, $themeList, $chainFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(\Magento\Framework\View\Asset\LocalInterface $asset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getContent');
        if (!$pluginInfo) {
            return parent::getContent($asset);
        } else {
            return $this->___callPlugins('getContent', func_get_args(), $pluginInfo);
        }
    }
}
