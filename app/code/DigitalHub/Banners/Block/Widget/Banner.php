<?php
namespace DigitalHub\Banners\Block\Widget;

class Banner extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = "widget/default.phtml";

    public function getAllBanners()
    {
        $maxBanners = 10;
        $banners = [];
        for($i = 1; $i <= $maxBanners; $i++){
            if(!empty($this->getData('image' . $i))){
                $banners[] = [
                    'link' => $this->getData('link_image' . $i),
                    'image' => $this->getData('image' . $i),
                    'name' => $this->getData('name' . $i),
                    'position' => $i
                ];
            }
        }
        return $banners;
    }
}
