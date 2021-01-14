# Módulo DigitalHub_CustomerAttributes

Módulo para inserção de campos adicionais e validações para o padrão brasileiro.

## Problema principal

Quando o Magento está com o Guest Checkout habilitado, ele não exibe o campo `taxvat`, mas sim o `tax_id`.
No caso da criação de conta, quando configurado, este sempre mostrará o campo `taxvat`.

Neste caso vamos gravar os dados no `taxvat` para criação de conta e em caso de guest checkout ativo, copiaremos os dados do `tax_id` para o `taxvat`.

## Mapeamento de campos

Tipo de cadastro => account_type (pf = Pessoa física, pj = Pessoa jurídica)
CPF/CNPJ => taxvat + tax_id (em caso de guest checkout com cópia para o customer, se este se registrar)
Razão Social => razao_social
Nome Fantasia => nome_fantasia
IE => ie
Endereço => padrão de 4 linhas (endereço, número, complemento e bairro)

Atributos adicionados pelo módulo:

- Tipo de cadastro (account_type) na entidade Customer
- Razão Social (razao_social) na entidade Customer
- Nome Fantasia (nome_fantasia) na entidade Customer
- Inscrição Estadual (ie) na entidade Customer

#### Importante: o uso de contas do tipo pessoa jurídica ficará restrito à compras com usuário registrado, ou seja, somente pessoa física poderá comprar no modo de Guest Checkout.


# Configurações necessárias:

- Endereço em 4 linhas (Configurações / Clientes / Configurações de clientes / Opções de nome e endereço / Number of Lines in a Street Address = 4)
- Posição do formulário de billing address após os métodos de pagamento (Configuraç / Vendas / Checkout / Checkout Options / Display Billing Address On = 'Payment Page')
