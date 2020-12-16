<?php
namespace Itatiaia\Custom\Plugin;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\ObjectManagerInterface;

class PluginBtnForceCancel
{
    protected $_backendUrl;

    public function __construct(
        UrlInterface $backendUrl
    ) {
        $this->_backendUrl = $backendUrl;
    }

    public function afterCanCancel(\Magento\Sales\Model\Order $subject, $result)
    {
        if(!$result){
            if($subject->getState() == 'payment_review'){
                return true;
            }
        }
        return $result;
    }

}
