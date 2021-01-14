# Módulo DigitalHub_AdditionalPrices

Módulo para exibição de parcelamento e preço à vista no produto.

## Para listagens de produtos

Adicionar o trecho de código abaixo no template da listagem, em cada produto:

```
<?php
    echo $this->getLayout()
        ->createBlock('\DigitalHub\AdditionalPrices\Block\Product\Additional')
        ->setTemplate('DigitalHub_AdditionalPrices::product/additional_prices.phtml')
        ->setProduct($_product)
        ->toHtml();
?>
```

## Para página do produto

O bloco responsável pela exibição das informações é inserido via layout XML dinamicamente, logo abaixo do preço final do produto.
