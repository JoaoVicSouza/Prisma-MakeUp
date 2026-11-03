<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=acesso_negado');
    exit;
}

$nome = $_SESSION['usuario_nome'] ?? '';
$email = $_SESSION['usuario_email'] ?? '';
$mensagensErro = [
    'nome' => 'Informe seu nome completo, sem números.',
    'email' => 'Informe um endereço de e-mail válido.',
    'email_existente' => 'Este e-mail já está sendo utilizado por outra conta.',
    'servidor' => 'Não foi possível atualizar seus dados. Tente novamente.',
];
$erroPerfil = $_GET['erro'] ?? '';
$mensagemErro = is_string($erroPerfil) ? ($mensagensErro[$erroPerfil] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma-MakeUp - Alterar dados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/styleCad.css">
    <link rel="icon" href="../assets/logo-icon.png" type="image/png">
</head>
<body class="bg-bege">
    <?php include '../components/header.php'; ?>

    <main class="container-fluid">
        <div class="auth-card">
            <h1 class="fs-4 fw-bold mb-4 text-center">Alterar meus dados</h1>

            <?php if ($mensagemErro !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="../scriptsPHP/atualizarPerfil.php">
                <div class="mb-3">
                    <label for="perfilNome" class="form-label">Nome completo</label>
                    <input type="text" class="form-control" id="perfilNome" name="nome"
                        value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                        pattern="[A-Za-zÀ-ÿ]+([ '-][A-Za-zÀ-ÿ]+)+" maxlength="100" required>
                </div>
                <div class="mb-4">
                    <label for="perfilEmail" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="perfilEmail" name="email"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" maxlength="150" required>
                </div>
                <button type="submit" class="btn btn-login w-100 mb-2">
                    <i class="bi bi-save me-2"></i>Salvar alterações
                </button>
                <a href="perfil.php" class="btn btn-outline-secondary w-100">Cancelar</a>
            </form>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/scriptGeral.js"></script>
</body>
</html>
