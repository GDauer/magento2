<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\RedirectType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Redirect extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultPage->setPath('treinamento/rawtype/raw');

        return $resultPage;
    }
}
