<?php
// download.php - VERSÃO CORRIGIDA
require_once 'config.php';

$token = $_GET['token'] ?? '';

// Validação básica do token (32 caracteres hex)
if (!$token || !ctype_xdigit($token) || strlen($token) !== 32) {
    die('❌ Link de download inválido.');
}

$tokens_dir = __DIR__ . '/.tokens';
$token_file = $tokens_dir . '/' . $token . '.json';

// Verifica se arquivo do token existe
if (!file_exists($token_file)) {
    if (DEBUG_MODE) {
        error_log("[DOWNLOAD] Token não encontrado: {$token}");
    }
    die('❌ Este link de download não foi encontrado ou já expirou.');
}

// Lê e decodifica dados do token
$token_data = json_decode(file_get_contents($token_file), true);

if (!$token_data) {
    die('❌ Erro ao validar o link de download.');
}

// Verifica expiração (usa tempo do servidor atual)
if (time() > $token_data['expires']) {
    // Remove token expirado
    @unlink($token_file);
    die('❌ Este link de download expirou (válido por 30 minutos).');
}

// Verifica se já foi usado (opcional: permitir apenas 1 download)
if ($token_data['used'] ?? false) {
    die('❌ Este link de download já foi utilizado.');
}

// ✅ Token válido: marca como usado e libera download
$token_data['used'] = true;
$token_data['downloaded_at'] = time();
file_put_contents($token_file, json_encode($token_data), LOCK_EX);

// Limpeza automática de tokens antigos (1% das requisições)
if (rand(1, 100) === 1) {
    foreach (glob($tokens_dir . '/*.json') as $file) {
        $data = json_decode(file_get_contents($file), true);
        if (($data['expires'] ?? 0) < time()) {
            @unlink($file);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Download Liberado ✓</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f0fdf4; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .success { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 450px; text-align: center; border: 2px solid #22c55e; }
        .btn-download { 
            background: #22c55e; color: white; text-decoration: none; 
            padding: 14px 28px; border-radius: 8px; display: inline-block; 
            margin: 1rem 0; font-weight: 600; font-size: 1.1rem;
        }
        .btn-download:hover { background: #16a34a; }
        .info { font-size: 0.9rem; color: #64748b; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="success">
        <h1 style="color:#22c55e; margin-bottom:0.5rem">✅ Pagamento Confirmado!</h1>
        <p>Obrigado por comprar <strong><?php echo htmlspecialchars($token_data['product']); ?></strong></p>
        
        <a href="<?php echo htmlspecialchars($token_data['download_url']); ?>" target="_blank" class="btn-download">
            📥 BAIXAR PDF AGORA
        </a>
        
        <p class="info">
            💡 Dica: Salve o arquivo no seu dispositivo.<br>
            🔒 Link válido por 30 minutos e para uso único.
        </p>
        
        <p style="margin-top:1.5rem">
            <a href="index.php" style="color:#64748b; text-decoration:none">← Voltar para loja</a>
        </p>
    </div>

    <script>
        // Abre download automaticamente após 1s
        setTimeout(() => {
            window.open('<?php echo htmlspecialchars($token_data['download_url']); ?>', '_blank');
        }, 1000);
    </script>
</body>
</html>