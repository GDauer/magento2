<?php
namespace Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig\Structure\Element\Group;

/**
 * Proxy class for @see \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig\Structure\Element\Group
 */
class Proxy extends \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig\Structure\Element\Group implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig\Structure\Element\Group
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Smile\\ElasticsuiteCore\\Model\\Search\\Request\\RelevanceConfig\\Structure\\Element\\Group', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig\Structure\Element\Group
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible()
    {
        return $this->_getSubject()->isVisible();
    }

    /**
     * {@inheritdoc}
     */
    public function shouldCloneFields()
    {
        return $this->_getSubject()->shouldCloneFields();
    }

    /**
     * {@inheritdoc}
     */
    public function getCloneModel()
    {
        return $this->_getSubject()->getCloneModel();
    }

    /**
     * {@inheritdoc}
     */
    public function populateFieldset(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        return $this->_getSubject()->populateFieldset($fieldset);
    }

    /**
     * {@inheritdoc}
     */
    public function isExpanded()
    {
        return $this->_getSubject()->isExpanded();
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldsetCss()
    {
        return $this->_getSubject()->getFieldsetCss();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies($storeCode)
    {
        return $this->_getSubject()->getDependencies($storeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data, $scope)
    {
        return $this->_getSubject()->setData($data, $scope);
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return $this->_getSubject()->hasChildren();
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->_getSubject()->getChildren();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->_getSubject()->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_getSubject()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->_getSubject()->getLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getComment()
    {
        return $this->_getSubject()->getComment();
    }

    /**
     * {@inheritdoc}
     */
    public function getFrontendModel()
    {
        return $this->_getSubject()->getFrontendModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($key)
    {
        return $this->_getSubject()->getAttribute($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->_getSubject()->getClass();
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($fieldPrefix = '')
    {
        return $this->_getSubject()->getPath($fieldPrefix);
    }

    /**
     * {@inheritdoc}
     */
    public function getElementVisibility()
    {
        return $this->_getSubject()->getElementVisibility();
    }
}
