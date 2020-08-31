<?php
namespace Magento\Catalog\Model\Indexer\Product\Eav;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Indexer\Product\Eav
 */
class Interceptor extends \Magento\Catalog\Model\Indexer\Product\Eav implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Indexer\Product\Eav\Action\Row $productEavIndexerRow, \Magento\Catalog\Model\Indexer\Product\Eav\Action\Rows $productEavIndexerRows, \Magento\Catalog\Model\Indexer\Product\Eav\Action\Full $productEavIndexerFull)
    {
        $this->___init();
        parent::__construct($productEavIndexerRow, $productEavIndexerRows, $productEavIndexerFull);
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
