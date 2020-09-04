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
        $hideCarrierTitle = $this->scopeConfig->getValue('carriers/itatiaia_fixintelipost/hide_carrier_title');

        $firstDateParsed = date('d/m', strtotime($firstDate));

        if ($scheduled)
        {
            return $this->scopeConfig->getValue("carriers/intelipost/scheduled_title");
        }
        else
        {
            if (!$estimated_delivery)
            {
                $sameDayMessage = $this->scopeConfig->getValue("carriers/{$carrier}/same_day_title");
                if ($hideCarrierTitle) {
                    return $sameDayMessage;
                }
                return sprintf($sameDayMessage, $description);
            }
            else
            {
                $customTitleMessage = $this->scopeConfig->getValue("carriers/{$carrier}/custom_title");
                if ($hideCarrierTitle) {
                    return sprintf($customTitleMessage, $firstDateParsed, $lastDate);
                }

                return sprintf($customTitleMessage, $description, $firstDateParsed, $lastDate);
            }
        }
    }
}
