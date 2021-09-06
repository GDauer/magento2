<?php
namespace DigitalHub\CustomerAttributes\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data;

class Verify extends Action
{
   

    /**
     * @var CustomerFactory
     */
    private $_customerCollectionFactory;

    /**
     * @var Data $helper
     */
    private $helper;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RawFactory
     */
    private $resultRawFactory;


    /**
     * @inheritDoc
     */

    public function __construct(
        Context $context,
        Data $helper,
        JsonFactory $resultJsonFactory,
        RawFactory $resultRawFactory,
        CustomerFactory $customerCollectionFactory
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->_customerCollectionFactory = $customerCollectionFactory;
        
    }

    /**
     * Login registered users and initiate a session.
     *
     * Expects a POST. ex for JSON {"username":"user@magento.com", "password":"userpassword"}
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */


    public function execute()
    {
       
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        $taxvat = $this->getRequest()->getPost('taxvat');

        $response = [];
        
         //validate Taxvat is Unique
         if ($this->_hasTaxvat($taxvat)) {

            $response = [
                'error' => true
            ];
        }


         /** @var \Magento\Framework\Controller\Result\Json $resultJson */
         $resultJson = $this->resultJsonFactory->create();
         return $resultJson->setData($response);
    }

    /**
     * Validate has Taxvat
     * @param $customer
     * @param $value
     * @return bool
     */
    private function _hasTaxvat( $value): bool
    {
        $customerCollection = $this->_customerCollectionFactory->create()->getCollection();
        $customerCollection->addAttribuTeToFilter('taxvat', $value);


        if ($customerCollection->getSize() > 0)
            return  true;


        return false;
    }
}
