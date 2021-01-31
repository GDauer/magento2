<?php

namespace Itatiaia\ValidateImages\Cron;

use DOMDocument;
use DOMXPath;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ValidateExternalImages
{
    protected const IS_ENABLED = 'cms/external_images/enabled';
    protected const EMAIL_LIST = 'cms/external_images/recipients';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var StateInterface
     */
    private $inlineTranslation;
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(StoreManagerInterface $storeManager,
                                LoggerInterface $logger,
                                ScopeConfigInterface $scopeConfig,
                                StateInterface $inlineTranslation,
                                TransportBuilder $transportBuilder)
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $logger;
    }

    /*
     * Validate the images on the specified URLs.
     *
     * TODO:
     *  - add preferences to admin screen, so the list can be changed there;
     *  - also add a preference for recipients
     */
    public function validate()
    {
//        $this->logger->info('Inicializando a tarefa de validação de imagens externas.');
        $isEnabled = $this->scopeConfig->getValue(self::IS_ENABLED);

        if (!$isEnabled) {
//            $this->logger->notice('A validação está desativada.');
            return;
        }

        // Which is the server URL - used for comparison later
        // The correct way of getting it is this one, commented because debug
        //$baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $baseUrl = 'https://loja.cozinhasitatiaia.com.br';

        // The remote URLs that will have its contents fetched
        $remoteUrls = [
            'Home' => 'https://loja.cozinhasitatiaia.com.br/',
            'Cozinhas' => 'https://loja.cozinhasitatiaia.com.br/cozinhas.html',
            'Fogões' => 'https://loja.cozinhasitatiaia.com.br/fogoes.html',
            'Cooktops' => 'https://loja.cozinhasitatiaia.com.br/cooktops.html',
            'Módulos' => 'https://loja.cozinhasitatiaia.com.br/modulos.html',
            'Multiuso' => 'https://loja.cozinhasitatiaia.com.br/multiusos.html'
        ];

//        $this->logger->info('URL de base e URLs remotas', [$baseUrl, $remoteUrls]);

        // Initialize the array that'll hold all the remote images
        $remoteImages = [];

        foreach ($remoteUrls as $category => $url) {
            $pageContents = $this->getPageContents($url);

            if (!$pageContents) {
//                $this->logger->error('Não foi possível obter os dados da página.', [$url]);
                continue;
            }

            $images = $this->getPageImages($pageContents);

            if (empty($images)) {
//                $this->logger->notice('Nenhuma imagem encontrada na página.', [$url]);
                continue;
            }

            foreach ($images as $image) {
                if (strpos($image, $baseUrl) === false) {
                    $remoteImages[$category][] = $image;
                }
            }
        }
        if (!empty($remoteImages)) {
//            $this->logger->info('Imagens em servidores remotos', $remoteImages);
            try {
                $this->sendAlertMail($remoteImages);
//                $this->logger->info('Enviado e-mail com notificação de imagens externas.');

            } catch (MailException $e) {
//                $this->logger->error('Não foi possível enviar o email.', [$e->getMessage()]);
            }
        }

//        $this->logger->info('Finalizada a execução de validação de imagens externas.');
    }

    /**
     * Fetch the specified URL HTML contents
     *
     * @param $url string The remote URL
     * @return bool|string Return the page contents on success, false on failure.
     */
    private function getPageContents($url): ?string
    {
        $curlResource = curl_init($url);
        curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlResource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlResource, CURLOPT_AUTOREFERER, true);

        $pageContents = curl_exec($curlResource);
        curl_close($curlResource);

        return $pageContents;
    }

    /**
     * Get all the images from HTML contents
     *
     * @param $pageContents string The HTML contents
     * @return array An array with all the images from the HTML contents
     */
    private function getPageImages($pageContents): array
    {
        $page = new DOMDocument();
        //libxml_use_internal_errors(true);
        $page->loadHTML($pageContents, LIBXML_NOERROR);

        $xpath = new DOMXPath($page);
        $imageTags = $xpath->query("//img/@src");
        $imageSources = [];

        for ($i = 0; $i < $imageTags->length; $i++) {
            if (!empty($imageTags->item($i)->nodeValue)) {
                $imageSources[] = $imageTags->item($i)->nodeValue;
            }
        }

        return $imageSources;
    }

    /**
     * Send the email alerting of the existence of external images
     *
     * @param $remoteImages
     * @throws MailException
     */
    private function sendAlertMail($remoteImages)
    {
        $sender = [
            'name' => $this->scopeConfig->getValue('trans_email/ident_general/name', ScopeInterface::SCOPE_STORE),
            'email' => $this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE)
        ];

        $recipients = $this->scopeConfig->getValue(self::EMAIL_LIST);

        if(is_null($recipients) || empty($recipients)) {
//            $this->logger->notice('Nenhum destinatário definido.');
            return;
        }

        $recipients = explode(',', $recipients);

        $this->inlineTranslation->suspend();

        $transport = $this->transportBuilder->setTemplateIdentifier('external_images_alert')
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars(['image_list' => $remoteImages])
            ->setFrom($sender);

        if (!empty($recipients) && !is_null($recipients)) {
            foreach ($recipients as $recipient) {
                $transport->addTo($recipient);
            }
        }
        $transport->getTransport()
            ->sendMessage();
    }
}