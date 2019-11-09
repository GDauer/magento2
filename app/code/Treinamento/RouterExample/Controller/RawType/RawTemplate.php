<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\RawType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Element\Template;

class RawTemplate extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $block = $this->_view->getLayout()->createBlock(Template::class);
        $block->setTemplate('Treinamento_RouterExample::example.phtml');
        $result->setContents($block->toHtml());
        return $result;
    }
}
