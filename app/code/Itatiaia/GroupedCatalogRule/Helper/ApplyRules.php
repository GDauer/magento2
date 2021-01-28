<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 *
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 *
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Itatiaia\GroupedCatalogRule\Helper;

use Magento\CatalogRule\Helper\Data;

class ApplyRules
{
    /** @var Data */
    private $data;

    /**
     * ApplyRules constructor.
     *
     * @param Data $data
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    /**
     * validate method
     *
     * @param $rules
     *
     * @param $finalPrice
     *
     * @return float|int
     */
    public function apply($rules, $finalPrice)
    {
        if($rules){
            foreach($rules as $rule){
                $finalPrice = $this->data->calcPriceRule($rule['action_operator'], $rule['action_amount'], $finalPrice);
                if((int)$rule['action_stop']){
                    break;
                }
            }
        }
        return $finalPrice;
    }
}
