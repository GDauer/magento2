<?php
namespace Magento\Widget\Model\Widget;

/**
 * Interceptor class for @see \Magento\Widget\Model\Widget
 */
class Interceptor extends \Magento\Widget\Model\Widget implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Escaper $escaper, \Magento\Widget\Model\Config\Data $dataStorage, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\View\Asset\Source $assetSource, \Magento\Framework\View\FileSystem $viewFileSystem, \Magento\Widget\Helper\Conditions $conditionsHelper)
    {
        $this->___init();
        parent::__construct($escaper, $dataStorage, $assetRepo, $assetSource, $viewFileSystem, $conditionsHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function getWidgetDeclaration($type, $params = array(), $asIs = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWidgetDeclaration');
        if (!$pluginInfo) {
            return parent::getWidgetDeclaration($type, $params, $asIs);
        } else {
            return $this->___callPlugins('getWidgetDeclaration', func_get_args(), $pluginInfo);
        }
    }
}
