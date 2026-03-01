<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Open Graph Meta Tags para WhatsApp -->
    <meta property="og:title" content="<?php echo htmlspecialchars(PRODUCT_NAME); ?>">
    <meta property="og:description" content="Conteúdo exclusivo em PDF com técnicas avançadas. Apenas R$ <?php echo number_format(PRODUCT_PRICE_CENTS / 100, 2, ',', '.'); ?>">
    <meta property="og:image" content="https://www.emplasol.com.br/favImage.png">
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo htmlspecialchars(PRODUCT_NAME); ?>">

    <title><?php echo PRODUCT_NAME; ?></title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            margin: 0;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .card {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 24px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .product-title {
            margin: 0 0 0.75rem 0;
            font-size: 1.9rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.3;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .share-link {
            font-size: 0.8rem;
            color: #9ca3af;
            margin: 0;
            opacity: 0.7;
        }

        .share-link a {
            color: #25d366;
            text-decoration: none;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 12px;
            background: rgba(37, 211, 102, 0.08);
            border: 1px solid rgba(37, 211, 102, 0.15);
            transition: all 0.2s;
            font-size: 0.75rem;
        }

        .share-link a:hover {
            background: rgba(37, 211, 102, 0.15);
            transform: translateY(-1px);
            opacity: 1;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1.05rem;
            text-align: center;
            font-weight: 500;
        }

        .price {
            font-size: 2.8rem;
            color: #22c55e;
            font-weight: 900;
            margin: 1.5rem 0;
            text-align: center;
            text-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }

        .btn-buy {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white;
            border: none;
            padding: 20px;
            font-size: 1.2rem;
            border-radius: 16px;
            cursor: pointer;
            width: 100%;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 10px 35px rgba(124, 58, 237, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 1.5rem 0;
        }

        .btn-buy:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 15px 45px rgba(124, 58, 237, 0.5);
        }

        .btn-buy:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
        }

        .input {
            width: 100%;
            padding: 18px;
            margin: 1rem 0;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1.1rem;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.95);
        }

        .input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
            transform: translateY(-2px);
        }

        .input::placeholder {
            color: #9ca3af;
        }

        .input:invalid:not(:placeholder-shown) {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .error {
            background: #fef2f2;
            border: 2px solid #fecaca;
            color: #b91c1c;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .secure {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 2rem;
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
            background: rgba(124, 58, 237, 0.1);
            padding: 14px;
            border-radius: 12px;
        }

        @media (max-width: 480px) {
            body {
                padding-bottom: 20px;
            }

            .card {
                padding: 2rem 1.5rem;
                border-radius: 24px;
            }

            .product-title {
                font-size: 1.75rem;
            }

            .price {
                font-size: 2.6rem;
            }

            .btn-buy {
                padding: 22px 18px;
                font-size: 1.25rem;
            }

            .share-link {
                font-size: 0.75rem;
            }

            .share-link a {
                font-size: 0.7rem;
                padding: 3px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <!-- Cabeçalho com título + compartilhar DISCRETO -->
        <div class="product-header">
            <h1 class="product-title"><?php echo PRODUCT_NAME; ?></h1>
            <p class="share-link">
                <a href="#" onclick="shareWhatsApp(); return false;">
                    💬 Compartilhar este eBook
                </a>
            </p>
        </div>

        <p class="subtitle">Conteúdo exclusivo em PDF com técnicas avançadas.</p>

        <div class="price">R$ <?php echo number_format(PRODUCT_PRICE_CENTS / 100, 2, ',', '.'); ?></div>

        <?php if (isset($_GET['erro']) && DEBUG_MODE): ?>
            <div class="error">
                ⚠️ Erro:
                <?php
                $erros = [
                    'api' => 'Erro na comunicação com InfinitePay',
                    'sem_url' => 'Não foi possível gerar link de pagamento',
                    'parametros' => 'Parâmetros inválidos',
                    'pagamento' => 'Pagamento não confirmado'
                ];
                echo $erros[$_GET['erro']] ?? 'Erro desconhecido';
                ?>
            </div>
        <?php endif; ?>

        <form action="checkout.php" method="POST" novalidate>
            <input type="email" name="email" class="input"
                placeholder="👤 Seu melhor e-mail"
                required autocomplete="email"
                oninvalid="this.setCustomValidity('Insira seu melhor e-mail')"
                oninput="this.setCustomValidity('')">
            <button type="submit" class="btn-buy" id="btnBuy">🛒 Comprar com Pix ou Cartão</button>
        </form>

        <div class="secure">
            🔒 Pagamento processado pela InfinitePay • SSL Criptografado
        </div>
    </div>

    <script>
        const currentUrl = encodeURIComponent(window.location.href);
        const productName = <?php echo json_encode(PRODUCT_NAME); ?>;
        const productPrice = <?php echo json_encode(number_format(PRODUCT_PRICE_CENTS / 100, 2, ',', '.')); ?>;
        const shareText = `${productName}\n\n💰 Apenas R$ ${productPrice}\n📄 Conteúdo exclusivo em PDF!\n\n🔗 ${currentUrl}`;

        function shareWhatsApp() {
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(shareText)}`;
            window.open(whatsappUrl, '_blank');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const emailInput = form.querySelector('input[type="email"]');
            const btnBuy = document.getElementById('btnBuy');

            emailInput.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.setCustomValidity('');
                } else {
                    this.setCustomValidity('Insira seu melhor e-mail');
                }
            });

            form.addEventListener('submit', function(e) {
                if (!emailInput.validity.valid) {
                    e.preventDefault();
                    emailInput.focus();
                    return false;
                }
                btnBuy.innerHTML = '⏳ Redirecionando...';
                btnBuy.disabled = true;
            });
        });
    </script>
</body>

</html>