<?php
namespace Magento\Catalog\Api\Data;

/**
 * Extension class for @see \Magento\Catalog\Api\Data\CategoryInterface
 */
class CategoryExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CategoryExtensionInterface
{
    /**
     * @return \Smile\ElasticsuiteVirtualCategory\Api\Data\VirtualRuleInterface|null
     */
    public function getVirtualRule()
    {
        return $this->_get('virtual_rule');
    }

    /**
     * @param \Smile\ElasticsuiteVirtualCategory\Api\Data\VirtualRuleInterface $virtualRule
     * @return $this
     */
    public function setVirtualRule(\Smile\ElasticsuiteVirtualCategory\Api\Data\VirtualRuleInterface $virtualRule)
    {
        $this->setData('virtual_rule', $virtualRule);
        return $this;
    }
}
