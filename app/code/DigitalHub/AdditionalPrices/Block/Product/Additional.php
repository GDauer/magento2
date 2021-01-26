<?php
namespace DigitalHub\AdditionalPrices\Block\Product;

class Additional extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->coreRegistry = $coreRegistry;

        $_product = $this->coreRegistry->registry('product');
        if($_product){
            $data['product'] = $_product;
        }

        parent::__construct($context, $data);
    }

    public function getMaxInstallment($_product, $customPrice = false)
    {
        $installments = $this->getInstallments($_product, $customPrice);
        if(count($installments)){
            $installments = array_reverse($installments);
            return $installments[0];
        }
        return null;
    }

    public function getMaxInstallmentNumber($_product, $customPrice = false)
    {
        $max_installment = $this->getMaxInstallment($_product, $customPrice);
        if($max_installment){
            return $max_installment['number'];
        }
        return 1;
    }

    public function getMaxInstallmentPrice($_product, $customPrice = false)
    {
        $max_installment = $this->getMaxInstallment($_product, $customPrice);
        if($max_installment){
            return $max_installment['price'];
        }
        // return $_product->getFinalPrice();
        return (float)$this->calculateFinalPrice($_product, $customPrice);
    }

    public function getPriceWithDiscount($_product, $customPrice = false)
    {
        $discount = (float)$this->getConfig('digitalhub_additionalprices_payment_discount/discount');
        $discount_value = (float)$this->calculateFinalPrice($_product, $customPrice) * ($discount / 100);
        return ((float)$this->calculateFinalPrice($_product, $customPrice) - $discount_value);
    }

    public function getConfig($key)
    {
        return $this->scopeConfig
                ->getValue('sales/' . $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function calculateFinalPrice($_product, $customPrice = false)
    {
        if($_product && !$customPrice){
            if($_product->getTypeId() == 'grouped'){
                $finalPrice = 0;
                $associatedProducts = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
                foreach ($associatedProducts as $item) {
                    $qty = $item->getQty() ? $item->getQty() : 1;
                    $childProduct = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Catalog\Model\Product')->load($item->getId());
                    $finalPrice = $finalPrice + ((float)$childProduct->getFinalPrice() * (int)$qty);
                }
                return $finalPrice;
            }
            return $_product->getFinalPrice();
        } else {
            return $customPrice;
        }
    }

    public function getInstallments($_product, $customPrice = false)
    {
        $maxInstallments = $this->getConfig('digitalhub_additionalprices_installments/max_installments');
		$minInstallmentValue = $this->getConfig('digitalhub_additionalprices_installments/min_installment_value');
		// $total = (float)$_product->getFinalPrice();
        $total = (float)$this->calculateFinalPrice($_product, $customPrice);

		if(!(int)$maxInstallments){
			$maxInstallments = 1;
		}

        $installments = [];

		foreach(range(1,$maxInstallments) as $number){
			$total_with_interest = $this->calculateTotalWithInterest($total, $number);
			$installment_total = $total_with_interest / $number;
			if($installment_total < $minInstallmentValue) continue;

			$installments[] = [
				'number' => $number,
				'price' => $installment_total,
				'total_with_interest' => $total_with_interest,
				'interest_rate' => (float)$this->getInterestRateFor($number)
			];
		}

        return $installments;
    }

    public function getInterestRateFor($number)
    {
        $interest_rates_enabled = json_decode($this->getConfig('digitalhub_additionalprices_installments/enable_interest_rate'));
        $interest_rates_config = json_decode($this->getConfig('digitalhub_additionalprices_installments/interest_rates'));

        if((int)$interest_rates_enabled){
            foreach($interest_rates_config as $item){
                if($item->number == $number){
                    return $item->value;
                }
            }
        }

        return 0;
    }

    public function calculateTotalWithInterest($total, $installments_number)
    {
        $interest_rate = $this->getInterestRateFor($installments_number);
        $finalTotal = (floatval($interest_rate / 100) * floatval($total) + floatval($total));
        return $finalTotal;
    }
}
