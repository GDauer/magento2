<?php


namespace DigitalHub\CouponReport\Block\Widget\Grid\Renderer;


use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\DataObject;

class OrderLink extends AbstractRenderer
{
    /**
     * @var UrlInterface
     */
    private $url;

    public function __construct(Context $context, UrlInterface $url, array $data = [])
    {
        parent::__construct($context, $data);
        $this->url = $url;
    }

    public function render(DataObject $row)
    {
        $orderId = $row->getData('increment_id');
        $url = $this->url->getUrl('sales/order/view/', ['order_id' => $row->getData('entity_id')]);
        return '<a href="' . $url . '" target="_blank">#' . $orderId . '</a>';
    }
}