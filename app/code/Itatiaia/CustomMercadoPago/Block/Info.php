<?php
namespace Itatiaia\CustomMercadoPago\Block;

/**
 * Class Info
 *
 * @package MercadoPago\Core\Block
 */
class Info extends \MercadoPago\Core\Block\Info
{
    /**
     * Prepare information specific to current payment method
     *
     * @param null | array $transport
     * @return \Magento\Framework\DataObject
     */
    protected function _prepareSpecificInformation($transport = null)
    {
      $transport = parent::_prepareSpecificInformation($transport);
      $data = [];
      
      $info = $this->getInfo();
      $paymentResponse = $info->getAdditionalInformation("paymentResponse");

      if(isset($paymentResponse['id'])){ 
        $title = __('Payment id (Mercado Pago)');
        $data[$title->__toString()] = $paymentResponse['id'];
      }

      if(isset($paymentResponse['card']) && isset($paymentResponse['card']['first_six_digits']) && isset($paymentResponse['card']['last_four_digits'])){
        $title = __('Card Number');
        $data[$title->__toString()] = $paymentResponse['card']['first_six_digits'] . "xxxxxx".$paymentResponse['card']['last_four_digits'];
      }

      if(isset($paymentResponse['card']) && isset($paymentResponse['card']['expiration_month']) && isset($paymentResponse['card']['expiration_year'])){
        $title = __('Expiration Date');
        $data[$title->__toString()] = $paymentResponse['card']['expiration_month'] . "/". $paymentResponse['card']['expiration_year'];          
      }

      if(isset($paymentResponse['card']) && isset($paymentResponse['card']['cardholder']) && isset($paymentResponse['card']['cardholder']['name'])){ 
        $title = __('Card Holder Name');
        $data[$title->__toString()] = $paymentResponse['card']['cardholder']['name'];       
      }

      if(isset($paymentResponse['payment_method_id'])){ 
        $title = __('Payment Method');
        $data[$title->__toString()] = ucfirst($paymentResponse['payment_method_id']);      
      }

      if(isset($paymentResponse['installments'])){ 
        $title = __('Installments');
        $data[$title->__toString()] = $paymentResponse['installments'];
      }

      if(isset($paymentResponse['statement_descriptor'])){
        $title = __('Statement Descriptor');
        $data[$title->__toString()] = $paymentResponse['statement_descriptor'];    
      }

      if(isset($paymentResponse['status'])){ 
        $title = __('Payment Status');
        $data[$title->__toString()] = ucfirst($paymentResponse['status']);  
      }

      if(isset($paymentResponse['status_detail'])){
        $title = __('Payment Status Detail');
        $data[$title->__toString()] = ucfirst($paymentResponse['status_detail']);
        unset($data[$title->__toString()]);

          if(isset($transport[$title->__toString()])){
              unset($transport[$title->__toString()]);
          }
          
      }


      // LINK TO TICKET
      if(isset($paymentResponse['transaction_details']) && $paymentResponse['transaction_details']['external_resource_url']){
        $link = "<a href='" . $paymentResponse['transaction_details']['external_resource_url'] . "' target='_blank'>Segunda via do Boleto</a> <br><br>";
//        $data['Segunda Via'] = $link;
        echo $link;
      }



      return $transport->setData(array_merge($data, $transport->getData()));
    }

}