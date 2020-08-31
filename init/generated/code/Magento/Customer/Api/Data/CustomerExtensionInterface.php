<?php
namespace Magento\Customer\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Customer\Api\Data\CustomerInterface
 */
interface CustomerExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return string|null
     */
    public function getAccountType();

    /**
     * @param string $accountType
     * @return $this
     */
    public function setAccountType($accountType);

    /**
     * @return string|null
     */
    public function getTelephone();

    /**
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * @return string|null
     */
    public function getRazaoSocial();

    /**
     * @param string $razaoSocial
     * @return $this
     */
    public function setRazaoSocial($razaoSocial);

    /**
     * @return string|null
     */
    public function getNomeFantasia();

    /**
     * @param string $nomeFantasia
     * @return $this
     */
    public function setNomeFantasia($nomeFantasia);

    /**
     * @return string|null
     */
    public function getIe();

    /**
     * @param string $ie
     * @return $this
     */
    public function setIe($ie);

    /**
     * @return string|null
     */
    public function getAmazonId();

    /**
     * @param string $amazonId
     * @return $this
     */
    public function setAmazonId($amazonId);

    /**
     * @return boolean|null
     */
    public function getIsSubscribed();

    /**
     * @param boolean $isSubscribed
     * @return $this
     */
    public function setIsSubscribed($isSubscribed);

    /**
     * @return string|null
     */
    public function getVertexCustomerCode();

    /**
     * @param string $vertexCustomerCode
     * @return $this
     */
    public function setVertexCustomerCode($vertexCustomerCode);
}
