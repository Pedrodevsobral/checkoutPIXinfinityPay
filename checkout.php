<?php
// checkout.php - VERSÃO DEBUG COMPLETA
// Salve como UTF-8 SEM BOM no seu editor!

// 1. Iniciar buffer de saída IMEDIATAMENTE (evita headers already sent)
ob_start();

// 2. Carregar config
require_once 'config.php';

// 3. Configurar erros para DEV
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/debug.log');
}

// Função de log com timestamp
function dlog($msg) {
    if (DEBUG_MODE) {
        $time = date('H:i:s');
        $log = "[{$time}] {$msg}\n";
        error_log($log, 3, __DIR__ . '/debug.log');
        // Também mostra na tela se estiver debugando
        echo "<!-- DEBUG: {$msg} -->\n";
    }
}

dlog("=== INÍCIO checkout.php ===");

// 4. Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    dlog("Método inválido: " . $_SERVER['REQUEST_METHOD']);
    header('Location: index.php');
    exit;
}

dlog("POST recebido: " . json_encode($_POST, JSON_UNESCAPED_UNICODE));

// 5. Validar dados mínimos
if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    dlog("Email inválido ou ausente");
    header('Location: index.php?erro=email');
    exit;
}

// 6. Gerar order_nsu único
$order_nsu = 'EMP_' . date('YmdHis') . '_' . bin2hex(random_bytes(4));
dlog("Order NSU: {$order_nsu}");

// 7. Montar payload EXATO para API
$payload = [
    'handle' => INFINITE_HANDLE,
    'redirect_url' => RETURN_SUCCESS_URL,
    'order_nsu' => $order_nsu,
    'items' => [
        [
            'quantity' => 1,
            'price' => (int)PRODUCT_PRICE_CENTS,
            'description' => PRODUCT_NAME
        ]
    ],
    'customer' => [
        'email' => $_POST['email']
    ]
];

dlog("Payload: " . json_encode($payload, JSON_UNESCAPED_UNICODE));

// 8. Validar payload antes de enviar
$validation_errors = [];
if (empty($payload['handle'])) $validation_errors[] = 'handle vazio';
if (strpos($payload['redirect_url'], 'https://') !== 0) $validation_errors[] = 'redirect_url não é HTTPS';
if (!is_int($payload['items'][0]['price']) || $payload['items'][0]['price'] <= 100) {
    $validation_errors[] = 'price deve ser inteiro > 100 (atual: ' . $payload['items'][0]['price'] . ')';
}

if (!empty($validation_errors)) {
    dlog("VALIDAÇÃO FALHOU: " . implode('; ', $validation_errors));
    echo "<pre>ERRO DE VALIDAÇÃO:\n" . implode("\n", $validation_errors) . "</pre>";
    exit;
}

// 9. Fazer requisição cURL com timeout e logs
dlog("Enviando para API InfinitePay...");
$start_time = microtime(true);

$ch = curl_init('https://api.infinitepay.io/invoices/public/checkout/links');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 20,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_VERBOSE => DEBUG_MODE // Logs detalhados do cURL
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$curl_info = curl_getinfo($ch);
curl_close($ch);

$elapsed = round(microtime(true) - $start_time, 2);
dlog("API Response - HTTP:{$http_code} | Tempo:{$elapsed}s | Erro:{$curl_error}");
dlog("Resposta: " . substr($response, 0, 500) . (strlen($response) > 500 ? '...' : ''));

// 10. Tratamento de erros da API
if ($curl_error) {
    dlog("❌ CURL ERROR: {$curl_error}");
    echo "<pre>Erro de conexão:\n{$curl_error}</pre>";
    exit;
}

if ($http_code !== 200) {
    dlog("❌ HTTP {$http_code}: {$response}");
    $error_data = json_decode($response, true);
    echo "<pre>Erro API ({$http_code}):\n";
    print_r($error_data);
    echo "</pre>";
    exit;
}

// 11. Parse e validação da resposta
$result = json_decode($response, true);

if (empty($result['url'])) {
    dlog("❌ URL não retornada na resposta: " . print_r($result, true));
    echo "<pre>Sem URL na resposta:\n" . print_r($result, true) . "</pre>";
    exit;
}

dlog("✅ URL de checkout recebida: " . $result['url']);

// 12. Salvar referência do pedido (com fallback se sessão falhar)
session_start();
$_SESSION['order_ref'] = [
    'nsu' => $order_nsu,
    'email' => $_POST['email'],
    'created' => time()
];
dlog("Sessão salva: order_ref = " . json_encode($_SESSION['order_ref']));

// 13. Limpar buffer e redirecionar
ob_end_clean(); // Limpa qualquer output de debug
header('Location: ' . $result['url'], true, 302);
exit;
?>