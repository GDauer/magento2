<?php
namespace DigitalHub\FeedManager\Block\Adminhtml\Feed\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        if($this->getId()){
            return [
                'label' => __('Remover'),
                'on_click' => 'deleteConfirm(\'' . __(
                    'Voce tem certeza que deseja remover este feed?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                    'class' => 'delete',
                    'sort_order' => 10
                ];
        }

        return [];
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getId()]);
    }
}
