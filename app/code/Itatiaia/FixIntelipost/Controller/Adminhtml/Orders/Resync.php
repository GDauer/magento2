<?php
namespace Itatiaia\FixIntelipost\Controller\Adminhtml\Orders;

class Resync extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $helper = $objectManager->get('Itatiaia\FixIntelipost\Helper\Data');
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $logger->info('RESYNC INTELIPOST', [$id]);

        try {
            $intelipostShipment = $objectManager->get('\Intelipost\Quote\Model\ShipmentFactory')->create();
            $intelipostShipment->load($id);
            $order_increment_id = $intelipostShipment->getOrderNumber();
            $order = $objectManager->get('\Magento\Sales\Model\OrderFactory')->create()->loadByIncrementId($order_increment_id);
            // $logger->info('ORDER INCREMENT ID', [$order->getId(), $order->getIncrementId()]);

            $order_id = $order->getId();
            $helper->updateIntelipostInvoice($order_id);
            $this->messageManager->addSuccess('Resincronização enviada');

            $intelipostShipment->setIntelipostStatus('pending');
            $intelipostShipment->save();

            $logger->info('RESYNC INTELIPOST OK', [$order_id]);
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
            $logger->info('RESYNC INTELIPOST ERROR', [$e->getMessage()]);
        }

		$result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		$result->setPath('intelipost_push/orders');

		return $result;
    }
}
