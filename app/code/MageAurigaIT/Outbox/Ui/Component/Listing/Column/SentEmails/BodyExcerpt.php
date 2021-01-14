<?php
namespace MageAurigaIT\Outbox\Ui\Component\Listing\Column\SentEmails;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use MageAurigaIT\Outbox\Ext\Html2Text;

class BodyExcerpt extends \Magento\Ui\Component\Listing\Columns\Column
{
    private $html2text;
    
    public function __construct(
        Html2Text $html2text,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->html2text = $html2text;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $this->html2text->setHtml(strip_tags($item["email_body"], "<style><script>"));
                $item["body_e"] = mb_strimwidth(trim($this->html2text->getText()), 0, 30, "...");
            }
        }
        return $dataSource;
    }
}
