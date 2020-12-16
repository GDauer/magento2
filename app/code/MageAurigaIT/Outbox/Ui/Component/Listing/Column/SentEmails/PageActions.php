<?php
namespace MageAurigaIT\Outbox\Ui\Component\Listing\Column\SentEmails;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                
                $id = "X";
                
                if (isset($item["id"])) {
                    $id = $item["id"];
                }
                
                $item[$name . '_html'] = "<button class='button'><span>".__('View')."</span></button>";
                $item[$name . '_resendbuttonlabel'] = __('Resend');
                $item[$name . '_body'] = $item['email_body'];
                $item[$name . '_id'] = $id;
                $item[$name . '_resendmailurl'] = $this->context->getUrl("ait_outbox/outbox/resend", ['id'=>$id]);
            }
        }
        
        return $dataSource;
    }
}
