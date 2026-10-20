<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma-MakeUp - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/styleCad.css">
    <link rel="icon" href="../assets/logo-icon.png" type="image/png">
</head>
<body class="bg-bege">
    <?php include '../components/header.php'; ?>
    <main class="container-fluid d-flex justify-content-center align-items-center">
    <?php
        $mensagem = '';
        $tipo = '';
        if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'cadastro') {
            $mensagem = 'Conta criada com sucesso! Faça login.';
            $tipo = 'success';
        } elseif (isset($_GET['erro'])) {
            $mensagens_erro = [
                'email_existente' => 'Este e-mail já está cadastrado.',
                'servidor'        => 'Erro interno. Tente novamente.',
                'credenciais' => 'Email ou senha inválidos.',
                'email_invalido' => 'Digite um e-mail válido',
                'login_necessario' => 'Faça login para finalizar sua compra.'
            ];
            $mensagem = $mensagens_erro[$_GET['erro']] ?? 'Ocorreu um erro.';
            $tipo = 'danger';
        }
        if ($mensagem):
    ?>
        <div class="alert alert-<?= $tipo ?> alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:9999; min-width:300px;" role="alert">
            <?= $mensagem ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="auth-card">
            <ul class="nav nav-pills nav-fill mb-4" id="authTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login"
                        type="button" role="tab" aria-controls="login" aria-selected="true">Entrar</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="signup-tab" data-bs-toggle="pill" data-bs-target="#signup"
                        type="button" role="tab" aria-controls="signup" aria-selected="false">Criar conta</button>
                </li>
            </ul>

            <div class="tab-content" id="authTabContent">

                <section class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                    <div class="mb-3 text-center">
                        <h1 class="fs-4 fw-bold mb-1">Bem vindo(a) de volta!</h1>
                        <p class="text-secondary mb-0">Entre com seu e-mail e senha</p>
                    </div>
                    <form method="post" id="loginForm" action="../scriptsPHP/entrar.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="seu@email.com" required>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Sua senha" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Mostrar senha">
                                    <i class="icon-eye bi bi-eye-fill"></i>
                                    <i class="icon-eye-slash bi bi-eye-slash-fill d-none"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-login w-100 mt-4">Entrar</button>
                    </form>
                </section>

                <section class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                    <div class="mb-3 text-center">
                        <h1 class="fs-4 fw-bold mb-1">Crie sua conta</h1>
                        <p class="text-secondary mb-0">Preencha os dados abaixo</p>
                    </div>
                    <form method="post" id="signupForm" action="../scriptsPHP/cadastro.php">
                        <div class="mb-3">
                            <label for="nameSignup" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="name" id="nameSignup" placeholder="Seu nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailSignup" class="form-label">E-mail</label>
                            <input type="text" class="form-control" name="email" id="emailSignup"
                                placeholder="seu@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordSignup" class="form-label">Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="passwordSignup"
                                    placeholder="Sua senha" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Mostrar senha">
                                    <i class="icon-eye bi bi-eye-fill"></i>
                                    <i class="icon-eye-slash bi bi-eye-slash-fill d-none"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-signup w-100 mt-4">Criar conta</button>
                    </form>
                </section>

            </div>
        </div>
    </main>    

    <?php include '../components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="../scripts/scriptGeral.js"></script>
    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = btn.closest('.input-group').querySelector('input');
                const eyeIcon = btn.querySelector('.icon-eye');
                const eyeSlashIcon = btn.querySelector('.icon-eye-slash');
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                eyeIcon.classList.toggle('d-none', isHidden);
                eyeSlashIcon.classList.toggle('d-none', !isHidden);
                btn.setAttribute('aria-label', isHidden ? 'Esconder senha' : 'Mostrar senha');
            });
        });
    </script>
</body>
</html>