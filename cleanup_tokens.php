<?php
// cleanup_tokens.php - Execute periodicamente para limpar tokens expirados
require_once 'config.php';

$tokens_dir = __DIR__ . '/.tokens';
$deleted = 0;

if (is_dir($tokens_dir)) {
    foreach (glob($tokens_dir . '/*.json') as $file) {
        $data = json_decode(file_get_contents($file), true);
        if (!$data || ($data['expires'] ?? 0) < time()) {
            @unlink($file);
            $deleted++;
        }
    }
}

echo "Tokens expirados removidos: {$deleted}";
?>