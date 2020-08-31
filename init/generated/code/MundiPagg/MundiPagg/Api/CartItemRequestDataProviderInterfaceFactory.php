<?php
namespace MundiPagg\MundiPagg\Api;

/**
 * Factory class for @see \MundiPagg\MundiPagg\Api\CartItemRequestDataProviderInterface
 */
class CartItemRequestDataProviderInterfaceFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\MundiPagg\\MundiPagg\\Api\\CartItemRequestDataProviderInterface')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \MundiPagg\MundiPagg\Api\CartItemRequestDataProviderInterface
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
