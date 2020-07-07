<?php

namespace DEG\CustomReports\Ui\Component\Listing\Column\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;

class Statusorder extends AbstractRenderer

{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Repository
     */
    private $attributeRepository;


    /**
     * Fornecedor constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\Product\Attribute\Repository $attributeRepository
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributeRepository,
        \Magento\Backend\Block\Context $context,
        array $data = []

    ) {
        parent::__construct($context, $data);
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Renders grid column
     *
     * @param   \Magento\Framework\DataObject $row
     * @return  string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $result = $row->getData('statusorder');
        $rowName = $row->getData('statusorder');

        if($rowName == "canceled" ){
            $result = "Cancelado";
        }

        if($rowName == "aguardando_analise_manual" ){
            $result = "Aguardando Analise Manual";
        }

        if($rowName == "payment_review" ){
            $result = "Aguardando confirmação de pagamento";
        }

        if($rowName == "processing" ){
            $result = "Processando";
        }

        if($rowName == "faturado_sap" ){
            $result = "Faturado SAP";
        }
        if($rowName == "complete" ){
            $result = "Completo";
        }

        return $result;

    }





}
