<?php

declare(strict_types=1);

namespace Treinamento\AdminPageExample\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 *
 * @package Treinamento\AdminPageExample\Controller\Adminhtml\Index
 */
class Index extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Treinamento_AdminPageExample::index';

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
