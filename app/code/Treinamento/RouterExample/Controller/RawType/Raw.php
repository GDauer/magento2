<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\RawType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Raw extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $resultPage->setContents('Hello World!');

        return $resultPage;
    }
}
