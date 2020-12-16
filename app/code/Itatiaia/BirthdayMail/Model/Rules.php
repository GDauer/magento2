<?php


namespace Itatiaia\BirthdayMail\Model;

use Magento\SalesRule\Model\ResourceModel\Rule\Collection;

class Rules implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * Rules constructor.
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $collection = $this->collection->addFieldToFilter('use_auto_generation', ['eq' => 1])
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->getData();
        $rules = [];
        foreach ($collection as $rule) {
            $rules[] = ['value' => $rule['rule_id'], 'label' => $rule['name']];
        }
        return $rules;
    }
}