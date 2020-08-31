<?php
namespace Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Interceptor class for @see \Magento\Framework\Reflection\DataObjectProcessor
 */
class Interceptor extends \Magento\Framework\Reflection\DataObjectProcessor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Reflection\MethodsMap $methodsMapProcessor, \Magento\Framework\Reflection\TypeCaster $typeCaster, \Magento\Framework\Reflection\FieldNamer $fieldNamer, \Magento\Framework\Reflection\CustomAttributesProcessor $customAttributesProcessor, \Magento\Framework\Reflection\ExtensionAttributesProcessor $extensionAttributesProcessor)
    {
        $this->___init();
        parent::__construct($methodsMapProcessor, $typeCaster, $fieldNamer, $customAttributesProcessor, $extensionAttributesProcessor);
    }

    /**
     * {@inheritdoc}
     */
    public function buildOutputDataArray($dataObject, $dataObjectType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'buildOutputDataArray');
        if (!$pluginInfo) {
            return parent::buildOutputDataArray($dataObject, $dataObjectType);
        } else {
            return $this->___callPlugins('buildOutputDataArray', func_get_args(), $pluginInfo);
        }
    }
}
