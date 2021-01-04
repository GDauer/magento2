# Módulo DigitalHub_EstimateShipping

Módulo para inserção da estimativa de frete na página do produto.

## Compatibilidade

Compatíveis:

- Produto simples
- Produto configurável
- Produto agrupado

Não compatíveis (não exibirá na página do produto):

- Produto bundle
- Produto virtual
- Produto para download

## Instalação

Importante: deve ser configurado o país padrão em Configurações / Geral / Opções de país (Country Options) e também em Configurações / Geral / Informações da Loja (Store Information).

Adicionar o seguinte trecho de código na posição do template do produto desejada:

```
<?php
    echo $this->getLayout()
        ->createBlock('\DigitalHub\EstimateShipping\Block\Estimate')
        ->setTemplate('DigitalHub_EstimateShipping::product/estimate-shipping.phtml')
        ->toHtml()
?>
```
