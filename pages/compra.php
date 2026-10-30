<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=login_necessario&origem=checkout');
    exit;
}

$pedidoCriado = $_SESSION['pedido_criado'] ?? null;
unset($_SESSION['pedido_criado']);

$mensagensErro = [
    'dados' => 'Confira os dados informados para entrega e pagamento.',
    'carrinho' => 'O carrinho está vazio ou contém dados inválidos. Volte à loja e tente novamente.',
    'estoque' => 'Não foi possível concluir o pedido. Um produto pode estar sem estoque; confira o carrinho.',
    'servidor' => 'Não foi possível registrar o pedido. Tente novamente.',
];
$mensagemErro = $mensagensErro[$_GET['erro'] ?? ''] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="icon" href="../assets/logo-icon.png" type="image/png">
    <style>
        body {
            min-height: 100dvh;
            background-image: url('../assets/backgrounds/5.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        main {
            padding-top: 150px;
            padding-bottom: 150px;
        }

        .checkout-card {
            width: 100%;
            max-width: 960px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .12);
            padding: 2rem;
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .thumb {
            width: 56px;
            height: 56px;
            border-radius: .65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .remove-item {
            color: #adb5bd;
            padding: .25rem .5rem;
        }

        .remove-item:hover {
            color: #dc3545;
        }

        .summary-box {
            background-color: var(--azul);
            border-radius: .75rem;
            padding: 1.25rem;
        }
        .summary-box .total-row {
            font-size: 1.15rem;
            font-weight: 700;
        }

        /* Inputs */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--azul);
            box-shadow: 0 0 0 .25rem color-mix(in srgb, var(--azul) 25%, transparent);
        }

        .payment-option {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border: 1px solid #ced4da;
            border-radius: .65rem;
            padding: .75rem;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            transition: all .15s ease-in-out;
        }

        .btn-check:checked+.payment-option {
            border-color: var(--roxo);
            background-color: color-mix(in srgb, var(--roxo) 10%, transparent);
            color: var(--roxo);
            font-weight: 600;
        }

        .payment-info {
            font-size: .85rem;
            color: #6c757d;
            background-color: var(--rosa);
            border-radius: .5rem;
            padding: .65rem .9rem;
            margin-top: .75rem;
        }

        .btn-checkout {
            background-color: var(--roxo);
            border-color: var(--roxo);
            color: #fff;
        }

        .btn-checkout:hover {
            background-color: var(--rosaChoque);
            border-color: var(--rosaChoque);
            color: #fff;
        }

        .btn-checkout:disabled {
            background-color: var(--rosa);
            border-color: var(--rosa);
        }

        .cep-hint {
            font-size: .8rem;
            color: #6c757d;
        }
    </style>
</head>

<body class="bg-bege">
    <?php include '../components/header.php' ?>
    <main class="container d-flex align-items-center justify-content-center">
        <div class="checkout-card mx-auto position-relative">
            <a href="produtos.php" class="position-absolute text-secondary text-decoration-none"
                style="top: 1.5rem; left: 1.5rem; font-size: 1.8rem; z-index: 10;"
                aria-label="Voltar para os produtos">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="fs-4 fw-bold mb-4 text-center">Finalizar compra</h1>

            <?php if ($mensagemErro !== null): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if (!$pedidoCriado): ?>
            <div class="row g-4">

                <!-- CARRINHO + RESUMO -->
                <div class="col-lg-7">
                    <p class="section-title">Seu carrinho</p>

                    <div id="cartItems"></div>

                    <p id="emptyCartMsg" class="text-secondary text-center py-4 d-none">Seu carrinho está vazio.</p>
                    <p id="cartErrorMsg" class="text-danger text-center py-4 d-none" role="alert"></p>

                    <div class="summary-box mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal">R$ 0,00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Frete</span>
                            <span id="shippingValue">A calcular</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between total-row">
                            <span>Total</span>
                            <span id="total">R$ 0,00</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <form method="post" id="checkoutForm" action="../scriptsPHP/addPedido.php">
                        <input type="hidden" name="cart" id="cartData">
                        <p class="section-title">Dados do destinatário</p>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label for="fullName" class="form-label">Nome completo</label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    placeholder="Seu nome completo" pattern="[A-Za-zÀ-ÿ]+([ '-][A-Za-zÀ-ÿ]+)+" maxlength="100" required>
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" inputmode="tel" autocomplete="tel"
                                    placeholder="(00) 00000-0000" pattern="[0-9() +.-]{10,20}" maxlength="20" required>
                            </div>
                        </div>

                        <p class="section-title">Endereço de entrega</p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000"
                                    maxlength="9" inputmode="numeric" required>
                                <span class="cep-hint" id="cepStatus"></span>
                            </div>
                            <div class="col-md-7">
                                <label for="street" class="form-label">Rua</label>
                                <input type="text" class="form-control" id="street" name="street"
                                    placeholder="Nome da rua" required>
                            </div>
                            <div class="col-md-4">
                                <label for="number" class="form-label">Número</label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="123" required>
                            </div>
                            <div class="col-md-8">
                                <label for="complement" class="form-label">Complemento <span
                                        class="text-secondary">(opcional)</span></label>
                                <input type="text" class="form-control" id="complement" name="complement"
                                    placeholder="Apto, bloco, referência...">
                            </div>

                            <div class="col-8">
                                <label for="city" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="Sua cidade" required>
                            </div>
                            <div class="col-4">
                                <label for="state" class="form-label">UF</label>
                                <select class="form-select" id="state" name="state" required>
                                    <option value="" selected disabled></option>
                                    <option>AC</option>
                                    <option>AL</option>
                                    <option>AP</option>
                                    <option>AM</option>
                                    <option>BA</option>
                                    <option>CE</option>
                                    <option>DF</option>
                                    <option>ES</option>
                                    <option>GO</option>
                                    <option>MA</option>
                                    <option>MT</option>
                                    <option>MS</option>
                                    <option>MG</option>
                                    <option>PA</option>
                                    <option>PB</option>
                                    <option>PR</option>
                                    <option>PE</option>
                                    <option>PI</option>
                                    <option>RJ</option>
                                    <option>RN</option>
                                    <option>RS</option>
                                    <option>RO</option>
                                    <option>RR</option>
                                    <option>SC</option>
                                    <option>SP</option>
                                    <option>SE</option>
                                    <option>TO</option>
                                </select>
                            </div>
                        </div>

                        <p class="section-title">Forma de pagamento</p>
                        <div class="row g-2 mb-1">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment" id="payPix" value="pix" checked>
                                <label class="payment-option" for="payPix">
                                    <i class="bi bi-qr-code"></i>
                                    Pix
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment" id="payBoleto" value="boleto">
                                <label class="payment-option" for="payBoleto">
                                    <i class="bi bi-bar-chart-steps"></i>
                                    Boleto
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-checkout w-100 mt-3" id="submitBtn">Finalizar
                            compra</button>
                    </form>
                </div>

            </div>
            <?php else: ?>
                <section class="text-center py-4" aria-labelledby="pedidoFinalizadoTitulo">
                    <div class="display-4 text-success mb-3"><i class="bi bi-check-circle-fill"></i></div>
                    <h2 id="pedidoFinalizadoTitulo" class="fw-bold mb-3">Compra finalizada!</h2>
                    <p class="text-secondary mb-2">Seu pedido <strong>#<?= (int) $pedidoCriado ?></strong> foi registrado com sucesso.</p>
                    <p class="text-secondary mb-4">Você poderá acompanhar as informações do pedido na área "Meus pedidos".</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="../index.php" class="btn btn-checkout">Voltar para a página principal</a>
                        <a href="produtos.php" class="btn btn-outline-secondary">Voltar para produtos</a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>
    <?php include '../components/footer.php' ?>
    
    <script src="../scripts/scriptGeral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?php if ($pedidoCriado): ?>
        <script>localStorage.removeItem('carrinhoPrisma');</script>
    <?php endif; ?>
    <script src="../scripts/scrpitCarrinho.js?v=<?= (int) @filemtime(__DIR__ . '/../scripts/scrpitCarrinho.js') ?>"></script>
    <script>
        const cepInput = document.getElementById('cep');
        const cepStatus = document.getElementById('cepStatus');

        if (cepInput && cepStatus) {
        cepInput.addEventListener('input', function () {
            let digits = cepInput.value.replace(/\D/g, '').slice(0, 8);
            cepInput.value = digits.length > 5 ? digits.slice(0, 5) + '-' + digits.slice(5) : digits;
        });

        cepInput.addEventListener('blur', function () {
            const digits = cepInput.value.replace(/\D/g, '');
            if (digits.length !== 8) return;

            cepStatus.textContent = 'Buscando endereço...';

            fetch('https://viacep.com.br/ws/' + digits + '/json/')
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.erro) {
                        cepStatus.textContent = 'CEP não encontrado. Preencha manualmente.';
                        return;
                    }
                    document.getElementById('street').value = data.logradouro || '';
                    document.getElementById('city').value = data.localidade || '';
                    document.getElementById('state').value = data.uf || '';
                    cepStatus.textContent = '';
                    document.getElementById('number').focus();
                })
                .catch(function () {
                    cepStatus.textContent = 'Não foi possível buscar o CEP. Preencha manualmente.';
                });
        });
        }
    </script>
</body>

</html>