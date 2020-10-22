<?php
namespace DigitalHub\Banners\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class WidgetObserver implements ObserverInterface
{
    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ReinitableConfigInterface $config,
        \Magento\Framework\UrlInterface $urlBuilder
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->directoryList = $directoryList;
        $this->urlBuilder = $urlBuilder;
        $this->_appConfig = $config;
    }

    public function execute(EventObserver $observer)
    {
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');

        if(get_class($observer->getObject()) == 'Magento\Widget\Model\Widget\Instance'){
            $widgetParams = json_decode($observer->getObject()->getData('widget_parameters'), true);

            for($i = 1; $i <= 10; $i++){
                if(isset($widgetParams['image' . $i])){
                    $widgetParams['image' . $i] = $this->parseImageLink($widgetParams['image' . $i]);
                }
            }
            $observer->getObject()->setData('widget_parameters', json_encode($widgetParams));
        }
    }

    public function parseImageLink($admin_directive_url)
    {
        if(strpos($admin_directive_url,'/directive/___directive/') !== false) {
            $parts = explode('/', $admin_directive_url);
            $key = array_search("___directive", $parts);
            if($key !== false) {
                $admin_directive_url = $parts[$key+1];
                $admin_directive_url = base64_decode(strtr($admin_directive_url, '-_,', '+/='));
                $parts = explode('"', $admin_directive_url);
                $key = array_search("{{media url=", $parts);
                $file_url = $parts[$key+1];
                return $this->urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $file_url;
            }
        }
        return $admin_directive_url;
    }
}
