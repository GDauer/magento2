<?php

namespace Itatiaia\FixIntelipost\Helper;

class Title extends \Intelipost\Quote\Helper\Data
{
    public function getCustomCarrierTitle($carrier = 'intelipost', $description, $estimated_delivery, $scheduled = false)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->get('\Itatiaia\FixIntelipost\Helper\Estimate');
        $firstDate = $helper->getDate($estimated_delivery);
        $lastDate = date('d/m', strtotime($firstDate . '+ 3 days'));

        $firstDateParsed = date('d/m', strtotime($firstDate));

        if ($scheduled)
        {
            return $this->scopeConfig->getValue("carriers/intelipost/scheduled_title");
        }
        else
        {
            if (!$estimated_delivery)
            {
                return sprintf($this->scopeConfig->getValue("carriers/{$carrier}/same_day_title"), $description);
            }
            else
            {
                $title = sprintf($this->scopeConfig->getValue("carriers/{$carrier}/custom_title"), $description, $firstDateParsed, $lastDate);
                return $title;
            }
        }
    }
}
