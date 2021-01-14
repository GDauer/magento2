<?php
namespace DigitalHub\Banners\Plugin;

use \Magento\Widget\Model\Widget as BaseWidget;

class Widget
{
    protected $_filter;

    public function __construct(
        \Magento\Cms\Model\Template\Filter $filter
    )
    {
        $this->_filter = $filter;
    }

    public function beforeGetWidgetDeclaration(BaseWidget $subject, $type, $params = [], $asIs = true)
    {

        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');

        if ($type == "DigitalHub\Banners\Block\Widget\Banner") {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
            $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
            $currentStore = $storeManager->getStore();

            // I rather do a check for a specific parameters
            foreach ($params as $paramKey => $value) {

                $url = $params[$paramKey];
                if (strpos($url, '/directive/___directive/') !== false) {

                    $parts = explode('/', $url);
                    $key = array_search("___directive", $parts);
                    if ($key !== false) {

                        $url = $parts[$key + 1];
                        $url = base64_decode(strtr($url, '-_,', '+/='));

                        $parts = explode('"', $url);
                        $key = array_search("{{media url=", $parts);
                        $url = $parts[$key + 1];

                        $imagePath = $this->_filter->filter($url);

                        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                        $imageUrl = $mediaUrl . $imagePath;

                        $logger->info($imageUrl);

                        $params[$paramKey] = $imageUrl;
                    }
                }
            }
        }

        return array($type, $params, $asIs);
    }
}
