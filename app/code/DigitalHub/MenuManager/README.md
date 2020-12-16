# Módulo DigitalHub_MenuManager

Após a instalação padrão do módulo, para renderizar um menu no template, adicionar o trecho de código no local desejado.


Top Menu (Magento_Theme/templates/html/topmenu.phtml):

```
<?php
echo $this->getLayout()
    ->createBlock('DigitalHub\MenuManager\Block\Menu')
    ->setTemplate('DigitalHub_MenuManager::menu/render.phtml')
    ->setIdentifier('topmenu')
    ->toHtml();
?>
```

Obs: "topmenu" é o identificador do menu cadastrado no painel de controle.

## Controle de cache

O módulo utiliza o cache padrão de blocos HTML do Magento, então após as alterações na edição do menu é necessário limpar o cache via painel para que as alterações surtam efeito.

## Menu para mobile

Na versão mobile, o menu sempre será renderizado sem os blocos estáticos de banners, e em uma árvore padronizada.
