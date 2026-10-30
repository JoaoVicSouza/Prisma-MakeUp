<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=acesso_negado');
    exit;
}

$nome  = $_SESSION['usuario_nome'] ?? 'Usuário';
$email = $_SESSION['usuario_email'] ?? '';
$mensagensPerfil = [
    'nome' => 'Informe seu nome completo, sem números.',
    'email' => 'Informe um endereço de e-mail válido.',
    'email_existente' => 'Este e-mail já está sendo utilizado por outra conta.',
    'servidor' => 'Não foi possível atualizar seus dados. Tente novamente.',
];
$mensagemPerfil = ($_GET['sucesso'] ?? '') === 'perfil'
    ? 'Seus dados foram atualizados com sucesso!'
    : ($mensagensPerfil[$_GET['erro'] ?? ''] ?? '');
$tipoMensagemPerfil = ($_GET['sucesso'] ?? '') === 'perfil' ? 'success' : 'danger';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma-MakeUp - Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/styleCad.css">
    <link rel="icon" href="../assets/logo-icon.png" type="image/png">
    <style>
        .auth-card { max-width: 520px; }

        .avatar-wrap {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--rosaChoque), var(--rosa));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 20px color-mix(in srgb, var(--roxo) 35%, transparent);
        }
        .avatar-wrap i { font-size: 2.2rem; color: #fff; }

        .profile-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--azul);
        }

        .divider {
            border-top: 1px solid color-mix(in srgb, var(--roxo) 25%, transparent);
            margin: 1.25rem 0;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid color-mix(in srgb, var(--roxo) 15%, transparent);
            font-size: 0.95rem;
            color: var(--roxo);
        }
        .info-row:last-child { border-bottom: none; }
        .info-row i { color: var(--rosaChoque); font-size: 1.1rem; width: 20px; }
        .info-label { color: var(--rosaChoque); font-size: 0.8rem; display: block; }

        .btn-perfil {
            background-color: var(--roxo);
            border-color: var(--roxo);
            color: #fff;
            font-weight: 600;
        }
        .btn-perfil:hover {
            background-color: var(--rosaChoque);
            border-color: var(--rosaChoque);
            color: #fff;
        }
        .btn-sair {
            background-color: transparent;
            border-color: var(--roxo);
            color: var(--roxo);
            font-weight: 600;
        }
        .btn-sair:hover {
            background-color: var(--rosa);
            color: var(--roxo);
        }
    </style>
</head>
<body class="bg-bege">


    <?php include '../components/header.php'; ?>

    <main class="container-fluid d-flex justify-content-center align-items-center">
        <div class="auth-card">

            <div class="text-center mb-3">
                <div class="avatar-wrap">
                    <i class="bi bi-person-fill"></i>
                </div>
                <p class="profile-name mb-0"><?= htmlspecialchars($nome) ?></p>
            </div>

            <div class="divider"></div>

            <!-- Informações -->
            <div class="mb-3">
                <div class="info-row">
                    <i class="bi bi-person"></i>
                    <div>
                        <span class="info-label">Nome</span>
                        <?= htmlspecialchars($nome) ?>
                    </div>
                </div>
                <div class="info-row">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <span class="info-label">E-mail</span>
                        <?= htmlspecialchars($email) ?>
                    </div>
                </div>

            </div>

            <div class="divider"></div>

            <?php if ($mensagemPerfil !== ''): ?>
                <div class="alert alert-<?= $tipoMensagemPerfil ?>" role="status">
                    <?= htmlspecialchars($mensagemPerfil, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="../scriptsPHP/atualizarPerfil.php" class="mb-4">
                <h2 class="fs-5 fw-bold mb-3">Alterar meus dados</h2>
                <div class="mb-3">
                    <label for="perfilNome" class="form-label">Nome completo</label>
                    <input type="text" class="form-control" id="perfilNome" name="nome"
                        value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                        pattern="[A-Za-zÀ-ÿ]+([ '-][A-Za-zÀ-ÿ]+)+" maxlength="100" required>
                </div>
                <div class="mb-3">
                    <label for="perfilEmail" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="perfilEmail" name="email"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" maxlength="150" required>
                </div>
                <button type="submit" class="btn btn-perfil w-100">
                    <i class="bi bi-save me-2"></i>Salvar alterações
                </button>
            </form>

            <div class="divider"></div>

            <!-- Ações -->
            <div class="d-flex flex-column gap-2">
                <a href="pedidos.php" class="btn btn-perfil w-100">
                    <i class="bi bi-box-seam me-2"></i>Meus pedidos
                </a>
                <a href="../pages/compra.php" class="btn btn-perfil w-100">
                    <i class="bi bi-cart me-2"></i>Ver carrinho
                </a>
                <a href="../scriptsPHP/sair.php" class="btn btn-sair w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>Encerrar sessão
                </a>
            </div>

        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/scriptGeral.js"></script>
</body>
</html>
