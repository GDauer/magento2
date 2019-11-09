<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\JsonType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Json extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultPage->setData('Hello World');

        return $resultPage;
    }
}
