<?php
require_once __DIR__ . '/../scriptsPHP/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare(
    'SELECT id, nome, categoria, descricao, preco, estoque, conteudo, validade,
            indicacao, cuidados, informacoes, imagem
     FROM produtos
     WHERE id = :id'
);
$stmt->execute([':id' => $id ?: 0]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Prisma-MakeUp - Produto</title>
    <style>
        body {
            background-attachment: fixed;
        }

        .produto-main {
            margin-top: 7rem !important;
        }

        .produto-card {
            width: 100%;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .12);
            padding: 2rem;
        }

        .produto-imagem {
            height: 500px;
            border-radius: 1rem;
        }

        @media (max-width: 575.98px) {
            .produto-card {
                padding: 1.25rem;
            }

            .produto-imagem {
                height: 360px;
            }
        }
    </style>

</head>

<body class="d-flex flex-column min-vh-100 bg-bege">
    <?php include '../components/header.php' ?>

    <main class="container mt-5 mb-5 pt-4 produto-main">
        <?php if (!$produto): ?>
            <div class="alert alert-warning mt-5" role="alert">
                Produto não encontrado.
                <a href="produtos.php" class="alert-link">Voltar para os produtos</a>
            </div>
        <?php else: ?>
        <div class="produto-card position-relative">
        <div id="notificacaoCarrinho" class="alert alert-success d-none position-fixed bottom-0 end-0 m-3 shadow"
            role="status" aria-live="polite" style="z-index: 1080;"></div>
        <a href="produtos.php" class="position-absolute text-secondary text-decoration-none"
            style="top: 1.5rem; left: 1.5rem; font-size: 1.8rem; z-index: 10;"
            aria-label="Voltar para os produtos">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="row gx-lg-5 py-5 align-items-center">

            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                <div class="produto-imagem w-100 d-flex justify-content-center align-items-center overflow-hidden">
                    <?php if (!empty($produto['imagem'])): ?>
                        <img src="../<?= htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>"
                            class="w-100 h-100" style="object-fit: cover;">
                    <?php else: ?>
                        <span class="text-muted">Imagem indisponível</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <h1 class="fw-bold text-dark mb-2" style="font-size: 2.5rem;"><?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-muted mb-4">Categoria: <?= htmlspecialchars($produto['categoria'], ENT_QUOTES, 'UTF-8') ?></p>

                <div class="fw-bold mb-4 py-2" style="font-size: 2rem; color: darkmagenta;">
                    R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?>
                </div>

                <p class="mb-4 py-4" style="font-size: 20px; line-height: 1.6; color: #555;">
                    <?= nl2br(htmlspecialchars($produto['descricao'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                </p>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <button type="button" class="btn w-100 h-100 fw-bold rounded-pill text-light shadow-sm py-3"
                            style="background-color: #DE97F2; transition: filter 0.2s;"
                            <?= (int) $produto['estoque'] <= 0 ? 'disabled' : '' ?>
                            data-acao-carrinho="adicionar"
                            data-id="<?= (int) $produto['id'] ?>"
                            data-nome="<?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>"
                            data-preco="<?= htmlspecialchars((string) $produto['preco'], ENT_QUOTES, 'UTF-8') ?>"
                            data-imagem="<?= htmlspecialchars(!empty($produto['imagem']) ? '../' . $produto['imagem'] : '', ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-bag-plus-fill me-1"></i> ADICIONAR AO CARRINHO
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn w-100 h-100 fw-bold rounded-pill text-light shadow-sm py-3"
                            style="background-color: #b64bd1; transition: filter 0.2s;"
                            <?= (int) $produto['estoque'] <= 0 ? 'disabled' : '' ?>
                            data-acao-carrinho="comprar"
                            data-id="<?= (int) $produto['id'] ?>"
                            data-nome="<?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>"
                            data-preco="<?= htmlspecialchars((string) $produto['preco'], ENT_QUOTES, 'UTF-8') ?>"
                            data-imagem="<?= htmlspecialchars(!empty($produto['imagem']) ? '../' . $produto['imagem'] : '', ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-bag-check-fill me-1"></i> COMPRAR AGORA
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-top pt-4">
            <h3 class="text-start">Detalhes do produto</h3>
            <?php foreach (['conteudo' => 'Conteúdo', 'validade' => 'Validade', 'indicacao' => 'Indicação', 'cuidados' => 'Cuidados', 'informacoes' => 'Informações técnicas'] as $campo => $rotulo): ?>
                <?php if (!empty($produto[$campo])): ?>
                    <p><strong><?= $rotulo ?>:</strong> <?= nl2br(htmlspecialchars($produto[$campo], ENT_QUOTES, 'UTF-8')) ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        </div>
        <?php endif; ?>
    </main>
    <?php include '../components/footer.php' ?>

    <script src="../scripts/scrpitCarrinho.js"></script>
    <script src="../scripts/scriptGeral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>

</html>