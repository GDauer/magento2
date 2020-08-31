<?php
namespace Magento\Catalog\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Catalog\Api\Data\CategoryInterface
 */
interface CategoryExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Smile\ElasticsuiteVirtualCategory\Api\Data\VirtualRuleInterface|null
     */
    public function getVirtualRule();

    /**
     * @param \Smile\ElasticsuiteVirtualCategory\Api\Data\VirtualRuleInterface $virtualRule
     * @return $this
     */
    public function setVirtualRule(\Smile\ElasticsuiteVirtualCategory\Api\Data\VirtualRuleInterface $virtualRule);
}
