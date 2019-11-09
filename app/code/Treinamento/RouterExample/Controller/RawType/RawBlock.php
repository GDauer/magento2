<?php

declare(strict_types=1);

namespace Treinamento\RouterExample\Controller\RawType;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Element\Text;
/** Can use this instead "_view->getLayout()" */
use Magento\Framework\View\Layout;

class RawBlock extends Action implements HttpGetActionInterface
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $block = $this->_view->getLayout()->createBlock(Text::class);
        $block->setText('Hello Word!!!');
        $result->setContents($block->toHtml());

        return $result;
    }
}
