<?php
namespace Itatiaia\FixIntelipost\Model\Request\ShipmentOrder;

class Customer extends \Intelipost\Push\Model\Request\ShipmentOrder\Customer
{
    public function getInformation($entity_id, $tax_vat)
	{
		$information = $this->selectCustomerAddress($entity_id);

		foreach($information as $info)
		{
			$this->getAddressData($info['street']);
			$this->first_name 			  = $info['firstname'];
			$this->last_name 			  = $info['lastname'];
			$this->email 				  = $info['email'];
			$this->phone 				  = $info['telephone'];
			$this->cellphone 			  = $info['telephone'];
			$this->is_company 			  = false;
			$this->federal_tax_payer_id   = $tax_vat;
			$this->shipping_city    	  = $info['city'];
			$this->shipping_state  		  = $info['region'];
			$this->shipping_zip_code	  = $info['postcode'];
			$this->shipping_country  	  = $info['country_id'];
		}
		return $this->prepareEndCustomerObj();
	}
}
