<?php


namespace Itatiaia\Mooble\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;

class Index extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Index constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Context $context, ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        if (empty($this->getIframeUrl())) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }

    /**
     * @return string|null
     */
    public function getIframeUrl() {
        return $this->scopeConfig->getValue('mooble/general/iframe_url',ScopeInterface::SCOPE_STORE);
    }
}