<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\ForwardType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Forward extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $resultPage->setModule('treinamento')
            ->setController('jsontype')
            ->forward('json');

        return $resultPage;
    }
}
