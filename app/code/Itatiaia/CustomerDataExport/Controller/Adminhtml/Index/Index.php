<?php


namespace Itatiaia\CustomerDataExport\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RedirectFactory;

class Index extends Action
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerCollectionFactory;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $orderCollectionFactory;
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        RedirectFactory $redirectFactory
    )
    {
        parent::__construct($context);
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->redirectFactory = $redirectFactory;
    }

    public function execute()
    {
        $result = $this->redirectFactory->create();

        $customers = $this->customerCollectionFactory->create();
        $orders = $this->orderCollectionFactory->create();

        $customerEmails = $this->getCustomerEmails($customers);
        $orderEmails = $this->getOrderEmails($orders);

        $intersect = array_intersect($customerEmails, $orderEmails);

        $csv = 'Clientes Cadastrados: ;';
        $csv .=  count($customerEmails) . ";" . "\n";

        $csv .= 'Clientes com Vendas: ;';
        $csv .=  count($orderEmails) . ";" . "\n";


        foreach ($intersect as $email) {
            $csv .= '"' . $email . '";'. "\n";
        }

        $this->getResponse ()
            ->setHttpResponseCode ( 200 )
            ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
            ->setHeader ( 'Pragma', 'public', true )
            ->setHeader ( 'Content-type', 'application/force-download' )
            ->setHeader ('Content-Disposition', 'attachment' . '; filename=datoscruzados.csv'   );
        $this->getResponse ()->clearBody ();
        $this->getResponse ()->sendHeaders ();

        echo $csv;

    }

    protected function getCustomerEmails($customers)
    {
        $customerEmails = [];
        foreach ($customers as $customer){
            $customerEmails[] = $customer->getEmail();
        }

        if(!is_array($customerEmails)){
            $customerEmails = [$customerEmails];
        }

        return $customerEmails;
    }

    protected function getOrderEmails($orders)
    {
        $orderEmails = [];
        foreach ($orders as $order){
            $orderEmails[] = $order->getCustomerEmail();
        }

        if(!is_array($orderEmails)){
            $orderEmails = [$orderEmails];
        }

        return $orderEmails;
    }
}