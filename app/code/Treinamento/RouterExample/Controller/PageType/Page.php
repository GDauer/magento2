<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\PageType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Page extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('Hello World'));

        return $resultPage;
    }
}
