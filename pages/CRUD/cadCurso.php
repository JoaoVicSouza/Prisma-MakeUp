<?php
require_once '../../scriptsPHP/adminAuth.php';
requireAdmin('../../index.php');
require '../../scriptsPHP/conexao.php';

$stmtProdutos = $pdo->query('SELECT id, nome FROM produtos ORDER BY nome');
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar curso de maquiagem</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../styles/styleGeral.css">
    <style>
        body {
            min-height: 100vh;
            background: var(--bege);
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 0;
            margin-top: 80px;
        }

        .form-card {
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .12);
            padding: 2rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--azul);
            box-shadow: 0 0 0 .25rem color-mix(in srgb, var(--azul) 25%, transparent);
        }

        .input-group:focus-within .form-control,
        .input-group:focus-within .input-group-text {
            border-color: var(--azul);
        }

        .input-group-text {
            background-color: var(--azul);
            color: #000;
            font-weight: 600;
        }

        .char-counter {
            font-size: .8rem;
            color: #6c757d;
        }

        .field-hint {
            font-size: .8rem;
            color: #6c757d;
        }

        .btn-save {
            background-color: var(--roxo);
            border-color: var(--roxo);
            color: #fff;
        }

        .btn-save:hover {
            background-color: var(--rosaChoque);
            border-color: var(--rosaChoque);
            color: #fff;
        }

        .btn-cancel {
            color: #6c757d;
        }
    </style>
</head>

<body>

    <?php include '../../components/header.php' ?>

    <main class="container">

        <div class="form-card mx-auto position-relative">
            <a href="admin.php" class="position-absolute text-secondary text-decoration-none"
                style="top: 1.5rem; left: 1.5rem; font-size: 1.8rem; z-index: 10;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="mb-4 text-center">
                <h1 class="fs-4 fw-bold mb-1">Cadastrar novo curso de maquiagem</h1>
                <p class="text-secondary mb-0">Preencha as informações do curso</p>
            </div>

            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php if ($_GET['erro'] === 'dados'): ?>
                        Confira os campos do curso.
                    <?php else: ?>
                        Não foi possível cadastrar o curso. Tente novamente.
                    <?php endif; ?>
                </div>
            <?php elseif (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success" role="status">Curso cadastrado com sucesso.</div>
            <?php endif; ?>

            <form method="post" id="courseForm" action="../../scriptsPHP/addCurso.php">
                <!-- Nome -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome do curso</label>
                    <input type="text" class="form-control" id="name" name="name"
                        maxlength="150" placeholder="Ex: Automaquiagem para o dia a dia" required>
                </div>

                <div class="mb-3">
                    <label for="produto_id" class="form-label">Nome do produto relacionado</label>
                    <?php if (empty($produtos)): ?>
                        <div class="alert alert-warning mb-0" role="alert">
                            Cadastre um produto antes de criar o curso.
                        </div>
                    <?php else: ?>
                        <select class="form-select" id="produto_id" name="produto_id" required>
                            <option value="" selected disabled>Selecione o produto</option>
                            <?php foreach ($produtos as $produto): ?>
                                <option value="<?= (int) $produto['id'] ?>">
                                    <?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <!-- Nível e Carga horária -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="level" class="form-label">Nível</label>
                        <select class="form-select" id="level" name="level" required>
                            <option value="" selected disabled>Selecione o nível</option>
                            <option>Iniciante</option>
                            <option>Intermediário</option>
                            <option>Avançado</option>
                            <option>Todos os níveis</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="cargaH" class="form-label">Carga horária</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="cargaH" name="cargaH" placeholder="0"
                                min="0" step="1" required>
                            <span class="input-group-text">horas</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="objetivo" class="form-label">Objetivo</label>
                    <input type="text" class="form-control" id="objetivo" name="objetivo" maxlength="255"
                        placeholder="Objetivo principal do curso">
                </div>

                <div class="mb-3">
                    <label for="conteudo" class="form-label">Conteúdo</label>
                    <textarea class="form-control" id="conteudo" name="conteudo" rows="4" maxlength="300"
                        placeholder="Descreva o conteúdo do curso"></textarea>
                </div>

                <div class="mb-4">
                    <label for="PublicoAlvo" class="form-label">Público-alvo</label>
                    <input type="text" class="form-control" id="PublicoAlvo" name="PublicoAlvo" maxlength="100"
                        placeholder="Para quem o curso é indicado">
                </div>

                <div class="d-flex gap-2">
                    <a href="admin.php" class="btn btn-cancel flex-shrink-0">Cancelar</a>
                    <button type="submit" class="btn btn-save w-100">Salvar curso</button>
                </div>
            </form>
        </div>
    </main>

    <?php include '../../components/footer.php' ?>

    <script src="../../scripts/scriptGeral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>