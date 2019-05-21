<?php


namespace Itatiaia\ProductTag\Controller\Adminhtml\Tag;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    public $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Itatiaia\ProductTag\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Itatiaia_ProductTag::Tag');
    }

    public function execute()
    {
        $fileId = $this->_request->getParam('param_name');
        try {
            $result = $this->imageUploader->saveFileToTmpDir($fileId);
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}