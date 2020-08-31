<?php
namespace Scandi\MagicZoom\Block\Gallery;

/**
 * Interceptor class for @see \Scandi\MagicZoom\Block\Gallery
 */
class Interceptor extends \Scandi\MagicZoom\Block\Gallery implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Stdlib\ArrayUtils $arrayUtils, \Scandi\MagicZoom\Helper\Config $config, \Magento\Framework\Json\EncoderInterface $jsonEncoder, array $data)
    {
        $this->___init();
        parent::__construct($context, $arrayUtils, $config, $jsonEncoder, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toHtml');
        if (!$pluginInfo) {
            return parent::toHtml();
        } else {
            return $this->___callPlugins('toHtml', func_get_args(), $pluginInfo);
        }
    }
}
