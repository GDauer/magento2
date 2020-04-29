<?php
namespace DigitalHub\FeedManager\Ui\Component\Listing\Column;

class Filename extends \Magento\Ui\Component\Listing\Columns\Column {

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $urlBuilder = $objectManager->get('Magento\Framework\UrlInterface');
            $emulation = $objectManager->get('\Magento\Store\Model\App\Emulation');

            foreach ($dataSource['data']['items'] as &$item) {
                if($item['status'] == 'generated'){
                    // start emulation
                    $emulation->startEnvironmentEmulation($item['store_id'], 'frontend', true);

                    $url = $urlBuilder->getBaseUrl() . 'media/feedxml/' . $item['filename'];
                    $item['filename'] = '<a href="'.$url.'" target="_blank">'.$item['filename'].'</a>';

                    // end emulation
                    $emulation->stopEnvironmentEmulation();
                } else {
                    $item['filename'] = $item['filename'];
                }
            }
        }
        return $dataSource;
    }
}
