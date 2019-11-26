<?php

namespace MageAurigaIT\Outbox\Model\Source;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve status options array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => \MageAurigaIT\Outbox\Plugin\MailArchivist::EMAIL_SENT_SUCCESS, 'label' => __('Sent')],
            ['value' => \MageAurigaIT\Outbox\Plugin\MailArchivist::EMAIL_SENT_FAIL, 'label' => __('Sending Failed')],
            ['value' => \MageAurigaIT\Outbox\Plugin\MailArchivist::EMAIL_SENT_UNDETERMINED, 'label' => __('Unknown')]
        ];
    }
}
