<?php
// retorno.php - VERSÃO CORRIGIDA
require_once 'config.php';

$slug = $_GET['slug'] ?? '';
$order_nsu = $_GET['order_nsu'] ?? '';
$transaction_nsu = $_GET['transaction_nsu'] ?? '';

if (DEBUG_MODE) {
    error_log("[RETORNO] Recebido: order_nsu={$order_nsu}");
}

if (!$order_nsu || !$slug) {
    header('Location: index.php?erro=parametros');
    exit;
}

// Consulta status na API
$payload = [
    'handle' => INFINITE_HANDLE,
    'order_nsu' => $order_nsu,
    'transaction_nsu' => $transaction_nsu,
    'slug' => $slug
];

$ch = curl_init('https://api.infinitepay.io/invoices/public/checkout/payment_check');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 15
]);
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

if (DEBUG_MODE) {
    error_log("[RETORNO] API response: " . json_encode($result));
}

// Verifica pagamento aprovado
if (isset($result['paid']) && $result['paid'] === true) {
    
    // ✅ TOKEN SEGURO: String aleatória + armazenamento em arquivo
    $token = bin2hex(random_bytes(16)); // 32 caracteres hex, URL-safe por natureza
    
    // Caminho para armazenar o token (usa pasta temporária do sistema ou cria uma local)
    $tokens_dir = __DIR__ . '/.tokens';
    if (!is_dir($tokens_dir)) {
        mkdir($tokens_dir, 0755, true);
    }
    
    $token_file = $tokens_dir . '/' . $token . '.json';
    
    // Dados do token (válido por 30 minutos)
    $token_data = [
        'order_nsu' => $order_nsu,
        'product' => PRODUCT_NAME,
        'download_url' => PDF_DRIVE_LINK,
        'created' => time(),
        'expires' => time() + 1800, // 30 minutos
        'used' => false
    ];
    
    // Salva token (LOCK para evitar race conditions)
    file_put_contents($token_file, json_encode($token_data), LOCK_EX);
    
    if (DEBUG_MODE) {
        error_log("[RETORNO] Token criado: {$token} | Arquivo: {$token_file}");
    }
    
    // Redireciona com token (hex é seguro para URL, sem encoding especial)
    header('Location: download.php?token=' . $token);
    exit;
    
} else {
    // Pagamento pendente - mostra página de aguardo
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Confirmando pagamento...</title>
        <meta http-equiv="refresh" content="10">
        <style>
            body { font-family: system-ui; text-align: center; padding: 50px; background: #f8fafc; }
            .spinner { width: 40px; height: 40px; border: 4px solid #e2e8f0; border-top-color: #7c3aed; border-radius: 50%; animation: spin 1s linear infinite; margin: 20px auto; }
            @keyframes spin { to { transform: rotate(360deg); } }
        </style>
    </head>
    <body>
        <h2>⏳ Aguardando confirmação...</h2>
        <div class="spinner"></div>
        <p>Atualizando automaticamente...</p>
    </body>
    </html>
    <?php
    exit;
}
?>