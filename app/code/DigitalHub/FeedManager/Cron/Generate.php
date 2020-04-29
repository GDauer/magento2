<?php
namespace DigitalHub\FeedManager\Cron;

class Generate {
    protected $logger;
    protected $feedHelper;
    protected $feedTemplateFactory;
    protected $feedCollectionFactory;

    public function __construct
    (
        \Psr\Log\LoggerInterface $logger,
        \DigitalHub\FeedManager\Helper\Feed $feedHelper,
        \DigitalHub\FeedManager\Model\ResourceModel\Feed\CollectionFactory $feedCollectionFactory,
        \DigitalHub\FeedManager\Model\Feed\TemplateFactory $feedTemplateFactory
    ) {
        $this->logger = $logger;
        $this->feedHelper = $feedHelper;
        $this->feedTemplateFactory = $feedTemplateFactory;
        $this->feedCollectionFactory = $feedCollectionFactory;
    }

    public function execute() {
        $collection = $this->feedCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('active', 1);
        $collection->load();

        foreach($collection as $feed){
            try {
                $template = $this->feedTemplateFactory->create()->load($feed->getTemplateId());
                $this->feedHelper->generateFeed($feed, $template);
                $this->logger->info('CRON FEED GENERATION', [$feed->getName()]);
            } catch (\Exception $e){
                $this->logger->info('CRON FEED ERROR', [$e->getMessage()]);
            }
        }
    }
}
