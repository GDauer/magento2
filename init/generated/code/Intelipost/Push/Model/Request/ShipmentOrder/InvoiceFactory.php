<?php
namespace Intelipost\Push\Model\Request\ShipmentOrder;

/**
 * Factory class for @see \Intelipost\Push\Model\Request\ShipmentOrder\Invoice
 */
class InvoiceFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Intelipost\\Push\\Model\\Request\\ShipmentOrder\\Invoice')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Intelipost\Push\Model\Request\ShipmentOrder\Invoice
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
