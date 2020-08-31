<?php
namespace Potato\Compressor\Block\Adminhtml\System\Config\Cache;

/**
 * Factory class for @see \Potato\Compressor\Block\Adminhtml\System\Config\Cache\Status
 */
class StatusFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Potato\\Compressor\\Block\\Adminhtml\\System\\Config\\Cache\\Status')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Potato\Compressor\Block\Adminhtml\System\Config\Cache\Status
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
