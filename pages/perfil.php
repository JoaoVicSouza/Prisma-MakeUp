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
    <style>
        .auth-card { max-width: 520px; }

        .avatar-wrap {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary2), var(--tertiary2));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 20px rgba(222, 151, 242, 0.35);
        }
        .avatar-wrap i { font-size: 2.2rem; color: #fff; }

        .profile-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #3f214f;
        }
        .profile-email {
            font-size: 0.9rem;
            color: #7a688a;
        }

        .divider {
            border-top: 1px solid rgba(222, 151, 242, 0.25);
            margin: 1.25rem 0;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(222, 151, 242, 0.15);
            font-size: 0.95rem;
            color: #3f214f;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row i { color: var(--primary2); font-size: 1.1rem; width: 20px; }
        .info-label { color: #7a688a; font-size: 0.8rem; display: block; }

        .btn-perfil {
            background-color: var(--primary2);
            border-color: var(--primary2);
            color: #fff;
            font-weight: 600;
        }
        .btn-perfil:hover {
            background-color: #c875ec;
            border-color: #c875ec;
            color: #fff;
        }
        .btn-sair {
            background-color: transparent;
            border-color: #ccc;
            color: #7a688a;
            font-weight: 600;
        }
        .btn-sair:hover {
            background-color: #f8f0fc;
            color: #3f214f;
        }
    </style>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=acesso_negado');
    exit;
}

$nome  = $_SESSION['usuario_nome'] ?? 'Usuário';
$email = $_SESSION['usuario_email'] ?? '';
?>

    <?php include '../components/header.php'; ?>

    <main class="container-fluid d-flex justify-content: center align-items-center">
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

            <!-- Ações -->
            <div class="d-flex flex-column gap-2">
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
