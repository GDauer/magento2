<?php


namespace Itatiaia\BirthdayMail\Cron;

use Itatiaia\BirthdayMail\Service\CodeGeneratorService;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

class SendBirthdayGiftMail
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var StateInterface
     */
    private $inlineTranslation;
    /**
     * @var CodeGeneratorService
     */
    private $codeGeneratorService;

    public function __construct(Context $context,
                                LoggerInterface $logger,
                                CollectionFactory $collectionFactory,
                                CustomerRepositoryInterface $customerRepository,
                                ScopeConfigInterface $scopeConfig,
                                TransportBuilder $transportBuilder,
                                StateInterface $inlineTranslation,
                                CodeGeneratorService $codeGeneratorService)
    {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->customerRepository = $customerRepository;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->codeGeneratorService = $codeGeneratorService;
    }

    /**
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     * @throws InputException
     * @throws InputMismatchException
     */
    public function execute()
    {
//        $this->logger->notice('Iniciando o cron de envio de email para aniversariantes');

        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('dob', [
                ['like' => date('%-m-d')],
            ]);

//        $this->logger->notice('Aniversariantes encontrados', [$collection->getData()]);

        /*
         * We iterate through the filtered collection
         */
        foreach ($collection->getData() as $item) {
            // Get the customer information
            $customer = $this->customerRepository->getById($item['entity_id']);

//            $this->logger->notice('Cliente atual', [$customer->getFirstname()]);

            $lastSentGiftMail = $customer->getCustomAttribute('dob_last_mail');

//            $this->logger->notice('Valor salvo do ultimo envio (caso exista)', [$lastSentGiftMail]);

            /*
             * If the result is null, this means that the email has never been sent.
             *
             * We can safely call sendMail here.
             */
            if (is_null($lastSentGiftMail)) {
//                $this->logger->notice('Nunca houve envio de email, enviando...');
                if ($this->sendMail($customer)) {
                    $customer->setCustomAttribute('dob_last_mail', date('Y-m-d H:i:s'));
                    $this->customerRepository->save($customer);
                }
            } else {
                /*
                 * Inside here it means that the email has been sent before. We'll check the dates here.
                 *
                 * We start by getting the last sent time.
                 */
                $lastSentDate = $customer->getCustomAttribute('dob_last_mail')->getValue();

//                $this->logger->notice('Data do ultimo envio', [$lastSentDate]);

                if (date('Y', strtotime($lastSentDate)) != date('Y')) {
//                    $this->logger->notice('Envio anterior a um ano, enviando...');
                    if ($this->sendMail($customer)) {
                        $customer->setCustomAttribute('dob_last_mail', date('Y-m-d H:i:s'));
                        $this->customerRepository->save($customer);
                    }
                }
            }
        }
    }

    /**
     * @param $customer
     * @throws MailException
     */
    private function sendMail($customer)
    {

        $codes = $this->codeGeneratorService->generateCodes();

//        $this->logger->notice('Codigo de desconto', $codes);

        if ($codes) {
            $variables = [
                'customer_name' => $customer->getFirstname(),
                'customer_email' => $customer->getEmail(),
                'coupon_code' => $codes[0]
            ];

//            $this->logger->notice('Variaveis do email', $variables);

            $sender = [
                'name' => $this->scopeConfig->getValue('trans_email/ident_general/name', ScopeInterface::SCOPE_STORE),
                'email' => $this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE)
            ];

            $this->inlineTranslation->suspend();

            $transport = $this->transportBuilder->setTemplateIdentifier('dob_gift_mail_template')
                ->setTemplateOptions([
                    'area' => Area::AREA_FRONTEND,
                    'store' => Store::DEFAULT_STORE_ID,
                ])
                ->setTemplateVars($variables)
                ->setFrom($sender)
                ->addTo($customer->getEmail())
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
            return true;
        } else {
            return false;
        }
    }
}