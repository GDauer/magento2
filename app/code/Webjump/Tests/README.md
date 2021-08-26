# Webjump Test Suite
Este módulo é responsável por rodar os testes unitários e de integração 
disparados pelo Travis.

## Testes Executados
São executados testes unitários desenvolvidos pela Webjump, além dos testes
estáticos (PHPCS, PHPMD, PHPCP e PS2) nos arquivos que foram alterados na 
branch.

## Como funciona
Atráves do arquivo `.travis.yml` localizado na raiz do projeto são disparados 
os testes necessários em cada push ou PR no repositório.

Atualmente são executados duas suites. Testes unitários e testes estáticos.
Eles são representados no trecho abaixo:

```yaml
env:
  matrix:
    - TEST_SUITE=unit
    - TEST_SUITE=static
```  
Caso seja necessário executar os dois testes em conjunto, trocar por:
```yaml
env:
  matrix:
    - TEST_SUITE=all
```

Por motivos de performance, e também por ser um pipeline novo no projeto, os 
testes estáticos são executados somente nos arquivos alterados na branch.
Caso seja necessário alterar esse comportamento, e rodar os testes estátivos no
projeto inteiro, alterar o arquivo `LiveCodeTest.php` no método `getWhitelist`
retornando o diretório onde devem ser executados os testes, como por exemplo:
```php
$files = [
    self::$pathToSource."/app/code/Webjump",
];

return $files;
```

Ao final de cada suite de testes é gerado um relatório com o Allure, e enviado
ao nosso servidor de desenvolvimento, onde pode ser acessado para que os 
problemas apontados sejam corrigidos.