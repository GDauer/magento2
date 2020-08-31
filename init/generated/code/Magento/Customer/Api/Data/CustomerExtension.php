<?php
namespace Magento\Customer\Api\Data;

/**
 * Extension class for @see \Magento\Customer\Api\Data\CustomerInterface
 */
class CustomerExtension extends \Magento\Framework\Api\AbstractSimpleObject implements CustomerExtensionInterface
{
    /**
     * @return string|null
     */
    public function getAccountType()
    {
        return $this->_get('account_type');
    }

    /**
     * @param string $accountType
     * @return $this
     */
    public function setAccountType($accountType)
    {
        $this->setData('account_type', $accountType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelephone()
    {
        return $this->_get('telephone');
    }

    /**
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->setData('telephone', $telephone);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRazaoSocial()
    {
        return $this->_get('razao_social');
    }

    /**
     * @param string $razaoSocial
     * @return $this
     */
    public function setRazaoSocial($razaoSocial)
    {
        $this->setData('razao_social', $razaoSocial);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNomeFantasia()
    {
        return $this->_get('nome_fantasia');
    }

    /**
     * @param string $nomeFantasia
     * @return $this
     */
    public function setNomeFantasia($nomeFantasia)
    {
        $this->setData('nome_fantasia', $nomeFantasia);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIe()
    {
        return $this->_get('ie');
    }

    /**
     * @param string $ie
     * @return $this
     */
    public function setIe($ie)
    {
        $this->setData('ie', $ie);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAmazonId()
    {
        return $this->_get('amazon_id');
    }

    /**
     * @param string $amazonId
     * @return $this
     */
    public function setAmazonId($amazonId)
    {
        $this->setData('amazon_id', $amazonId);
        return $this;
    }

    /**
     * @return boolean|null
     */
    public function getIsSubscribed()
    {
        return $this->_get('is_subscribed');
    }

    /**
     * @param boolean $isSubscribed
     * @return $this
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->setData('is_subscribed', $isSubscribed);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVertexCustomerCode()
    {
        return $this->_get('vertex_customer_code');
    }

    /**
     * @param string $vertexCustomerCode
     * @return $this
     */
    public function setVertexCustomerCode($vertexCustomerCode)
    {
        $this->setData('vertex_customer_code', $vertexCustomerCode);
        return $this;
    }
}
