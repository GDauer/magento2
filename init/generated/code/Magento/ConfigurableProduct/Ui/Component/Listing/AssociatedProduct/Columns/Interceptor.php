<?php
namespace Magento\ConfigurableProduct\Ui\Component\Listing\AssociatedProduct\Columns;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Ui\Component\Listing\AssociatedProduct\Columns
 */
class Interceptor extends \Magento\ConfigurableProduct\Ui\Component\Listing\AssociatedProduct\Columns implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\UiComponent\ContextInterface $context, \Magento\Catalog\Ui\Component\ColumnFactory $columnFactory, \Magento\Catalog\Ui\Component\Listing\Attribute\RepositoryInterface $attributeRepository, array $components = array(), array $data = array())
    {
        $this->___init();
        parent::__construct($context, $columnFactory, $attributeRepository, $components, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepare');
        if (!$pluginInfo) {
            return parent::prepare();
        } else {
            return $this->___callPlugins('prepare', func_get_args(), $pluginInfo);
        }
    }
}
