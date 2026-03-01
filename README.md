# Configuração Oficial InfinitePay

Este é o arquivo de configuração oficial do InfinitePay.

## Configurações Gerais

- `INFINITE_HANDLE`: Defina o nome da sua InfiniteTag sem o caractere `$`. Por exemplo, se a sua InfiniteTag for `$loja`, defina apenas `loja`.
- `BASE_URL`: Defina a URL base do seu site. Certifique-se de usar HTTPS em produção.
- `PDF_DRIVE_LINK`: Defina o link do PDF no Google Drive com acesso "Qualquer pessoa com o link".

## Exemplos de Configuração

Aqui estão alguns exemplos de como você pode configurar o arquivo `config.php`:

```php
define('INFINITE_HANDLE', 'loja');
define('BASE_URL', '[https://www.seusite.com.br](https://www.seusite.com.br)');
define('PDF_DRIVE_LINK', '[https://drive.google.com/file/d/SEU_ID/view?usp=sharing'](https://drive.google.com/file/d/SEU_ID/view?usp=sharing'));

```

## Instalação

1. Clone o repositório `https://github.com/Pedrodevsobral/checkoutPIXinfinityPay.git`.
2. Rode o PHP localmente ou em produção

## Uso

Se for localmente pode usar o comando `php -S localhost:80`

## Arquivo Config.php

O arquivo config.php inclui uma opção de modo de depuração. Para habilitar o modo de depuração, defina a constante DEBUG_MODE como true. Por exemplo:

´define('DEBUG_MODE', true);´

## Licença
Este arquivo de configuração está licenciado sob a Licença MIT.

