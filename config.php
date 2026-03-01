<?php
// config.php - CONFIGURAÇÃO OFICIAL INFINITEPAY

// ⚠️ SUA INFINITETAG SEM O $ (ex: se é $loja, use apenas "loja")
define('INFINITE_HANDLE', 'loja');

// URL base do seu site - OBRIGATÓRIO HTTPS EM PRODUÇÃO
define('BASE_URL', 'https://www.suaURL.com.br/');

// Link do PDF (Google Drive com acesso "Qualquer pessoa com o link")
define('PDF_DRIVE_LINK', 'https://drive.google.com/file/d/SEU_ID/view?usp=sharing');

// Dados do produto
define('PRODUCT_NAME', 'Apostila Ler Tacografo');
define('PRODUCT_PRICE_CENTS', 1000); // R$ 10,00 = 1000 CENTAVOS (inteiro!)

// URLs de retorno
define('RETURN_SUCCESS_URL', BASE_URL . '/retorno.php');
define('RETURN_ERROR_URL', BASE_URL . '/index.php?erro=checkout');

// Chave para assinar tokens de download (segurança)
define('DOWNLOAD_SECRET', 'develop!@#');

// Modo debug: mude para false em produção depois de testar
define('DEBUG_MODE', true);