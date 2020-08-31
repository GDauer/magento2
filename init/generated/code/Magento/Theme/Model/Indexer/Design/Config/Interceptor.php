<?php
namespace Magento\Theme\Model\Indexer\Design\Config;

/**
 * Interceptor class for @see \Magento\Theme\Model\Indexer\Design\Config
 */
class Interceptor extends \Magento\Theme\Model\Indexer\Design\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Indexer\StructureFactory $structureFactory, \Magento\Framework\Indexer\SaveHandlerFactory $saveHandlerFactory, \Magento\Framework\Indexer\FieldsetPool $fieldsetPool, \Magento\Framework\Indexer\HandlerPool $handlerPool, \Magento\Theme\Model\ResourceModel\Design\Config\Scope\CollectionFactory $collectionFactory, $data = array())
    {
        $this->___init();
        parent::__construct($structureFactory, $saveHandlerFactory, $fieldsetPool, $handlerPool, $collectionFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function executeFull()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'executeFull');
        if (!$pluginInfo) {
            return parent::executeFull();
        } else {
            return $this->___callPlugins('executeFull', func_get_args(), $pluginInfo);
        }
    }
}
