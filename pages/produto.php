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
$variacoes = [];
if ($produto) {
    $stmtVariacoes = $pdo->prepare(
        'SELECT id, rotulo, imagem
         FROM produto_variacoes
         WHERE produto_id = :produto_id
         ORDER BY ordem, id'
    );
    $stmtVariacoes->execute([':produto_id' => $produto['id']]);
    $variacoes = $stmtVariacoes->fetchAll(PDO::FETCH_ASSOC);
}
$categoriasPermitidas = ['pele', 'olhos', 'sobrancelhas', 'labios', 'kits'];
$categoriaVolta = $_GET['categoria'] ?? '';
if (!is_string($categoriaVolta) || !in_array($categoriaVolta, $categoriasPermitidas, true)) {
    $categoriaVolta = '';
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="icon" href="../assets/logo-icon.png" type="image/png">
    <title>Prisma-MakeUp - Produto</title>
    <style>
        body {
            background-attachment: fixed;
            background-image: url('../assets/backgrounds/5.jpg');
            background-size: cover;
            background-position: center;
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

        #carrosselVariacoes .carousel-control-prev,
        #carrosselVariacoes .carousel-control-next {
            top: 50%;
            bottom: auto;
            width: 3rem;
            height: 3rem;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 50%;
            background: linear-gradient(135deg, var(--roxo), #ff0198);
            box-shadow: 0 .4rem 1rem rgba(65, 24, 76, .28);
            opacity: 1;
            transform: translateY(-50%);
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        #carrosselVariacoes .carousel-control-prev {
            left: .75rem;
        }

        #carrosselVariacoes .carousel-control-next {
            right: .75rem;
        }

        #carrosselVariacoes .carousel-control-prev-icon,
        #carrosselVariacoes .carousel-control-next-icon {
            width: 1rem;
            height: 1rem;
            background-size: 100%;
        }

        #carrosselVariacoes .carousel-control-prev:hover,
        #carrosselVariacoes .carousel-control-next:hover {
            filter: saturate(1.15);
            box-shadow: 0 .6rem 1.25rem rgba(65, 24, 76, .36);
            transform: translateY(-50%) scale(1.08);
        }

        #carrosselVariacoes .carousel-control-prev:focus-visible,
        #carrosselVariacoes .carousel-control-next:focus-visible {
            outline: 3px solid rgba(149, 120, 210, .55);
            outline-offset: 3px;
        }

        #carrosselVariacoes .carousel-control-prev:active,
        #carrosselVariacoes .carousel-control-next:active {
            transform: translateY(-50%) scale(.96);
        }

        @media (prefers-reduced-motion: reduce) {
            #carrosselVariacoes .carousel-control-prev,
            #carrosselVariacoes .carousel-control-next {
                transition: none;
            }
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
        <a href="produtos.php<?= $categoriaVolta !== '' ? '#' . rawurlencode($categoriaVolta) : '' ?>" class="position-absolute text-secondary text-decoration-none"
            style="top: 1.5rem; left: 1.5rem; font-size: 1.8rem; z-index: 10;"
            aria-label="Voltar para os produtos">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="row gx-lg-5 py-5 align-items-center">

            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                <?php if ($variacoes): ?>
                    <div id="carrosselVariacoes" class="carousel slide">
                        <div class="carousel-inner produto-imagem overflow-hidden rounded">
                            <?php foreach ($variacoes as $indice => $variacao): ?>
                                <div
                                    class="carousel-item h-100 <?= $indice === 0 ? 'active' : '' ?>"
                                    data-variacao-id="<?= (int) $variacao['id'] ?>"
                                    data-variacao-rotulo="<?= htmlspecialchars($variacao['rotulo'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-imagem="<?= htmlspecialchars('../' . $variacao['imagem'], ENT_QUOTES, 'UTF-8') ?>">
                                    <img
                                        src="../<?= htmlspecialchars($variacao['imagem'], ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= htmlspecialchars($produto['nome'] . ' - ' . $variacao['rotulo'], ENT_QUOTES, 'UTF-8') ?>"
                                        class="w-100 h-100"
                                        style="object-fit: cover;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($variacoes) > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carrosselVariacoes" data-bs-slide="prev" aria-label="Opção anterior">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carrosselVariacoes" data-bs-slide="next" aria-label="Próxima opção">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-2 mt-3" role="group" aria-label="Escolha uma opção">
                        <?php foreach ($variacoes as $indice => $variacao): ?>
                            <button
                                type="button"
                                class="btn btn-sm <?= $indice === 0 ? 'bg-roxo text-white' : 'btn-outline-secondary' ?> seletor-variacao"
                                data-indice-variacao="<?= $indice ?>"
                                aria-pressed="<?= $indice === 0 ? 'true' : 'false' ?>">
                                <?= htmlspecialchars($variacao['rotulo'], ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-center text-secondary mt-2 mb-0" id="rotuloVariacaoSelecionada" aria-live="polite">
                        Opção: <?= htmlspecialchars($variacoes[0]['rotulo'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php else: ?>
                    <div class="produto-imagem w-100 d-flex justify-content-center align-items-center overflow-hidden">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="../<?= htmlspecialchars($produto['imagem'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                            <span class="text-muted">Imagem indisponível</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-lg-6">
                <h1 class="fw-bold text-dark mb-2" style="font-size: 2.5rem;"><?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-muted mb-4">Categoria: <?= htmlspecialchars($produto['categoria'], ENT_QUOTES, 'UTF-8') ?></p>

                <div class="fw-bold mb-4 py-2 text-rosaChoque" style="font-size: 2rem;">
                    R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?>
                </div>

                <p class="mb-4 py-4" style="font-size: 20px; line-height: 1.6; color: #555;">
                    <?= nl2br(htmlspecialchars($produto['descricao'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                </p>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <button type="button" class="btn w-100 h-100 fw-bold rounded-pill text-dark shadow-sm py-3 bg-azul"
                            style="transition: filter 0.2s;"
                            <?= (int) $produto['estoque'] <= 0 ? 'disabled' : '' ?>
                            data-acao-carrinho="adicionar"
                            data-id="<?= (int) $produto['id'] ?>"
                            data-nome="<?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>"
                            data-preco="<?= htmlspecialchars((string) $produto['preco'], ENT_QUOTES, 'UTF-8') ?>"
                            data-variacao-id="<?= isset($variacoes[0]) ? (int) $variacoes[0]['id'] : '' ?>"
                            data-variacao-rotulo="<?= htmlspecialchars($variacoes[0]['rotulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-imagem="<?= htmlspecialchars(isset($variacoes[0]) ? '../' . $variacoes[0]['imagem'] : (!empty($produto['imagem']) ? '../' . $produto['imagem'] : ''), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-bag-plus-fill me-1"></i> ADICIONAR AO CARRINHO
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn w-100 h-100 fw-bold rounded-pill text-white shadow-sm py-3 bg-rosaChoque"
                            style="transition: filter 0.2s;"
                            <?= (int) $produto['estoque'] <= 0 ? 'disabled' : '' ?>
                            data-acao-carrinho="comprar"
                            data-id="<?= (int) $produto['id'] ?>"
                            data-nome="<?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?>"
                            data-preco="<?= htmlspecialchars((string) $produto['preco'], ENT_QUOTES, 'UTF-8') ?>"
                            data-variacao-id="<?= isset($variacoes[0]) ? (int) $variacoes[0]['id'] : '' ?>"
                            data-variacao-rotulo="<?= htmlspecialchars($variacoes[0]['rotulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-imagem="<?= htmlspecialchars(isset($variacoes[0]) ? '../' . $variacoes[0]['imagem'] : (!empty($produto['imagem']) ? '../' . $produto['imagem'] : ''), ENT_QUOTES, 'UTF-8') ?>">
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
    <script>
        const carrosselVariacoes = document.getElementById('carrosselVariacoes');
        if (carrosselVariacoes) {
            const botoesVariacao = document.querySelectorAll('.seletor-variacao');
            const botoesCarrinho = document.querySelectorAll('[data-acao-carrinho]');
            const rotuloSelecionado = document.getElementById('rotuloVariacaoSelecionada');

            function atualizarVariacaoSelecionada(indice) {
                const slides = carrosselVariacoes.querySelectorAll('.carousel-item');
                const slide = slides[indice];
                if (!slide) return;

                botoesVariacao.forEach(function (botao, botaoIndice) {
                    const selecionado = botaoIndice === indice;
                    botao.classList.toggle('bg-roxo', selecionado);
                    botao.classList.toggle('text-white', selecionado);
                    botao.classList.toggle('btn-outline-secondary', !selecionado);
                    botao.setAttribute('aria-pressed', selecionado ? 'true' : 'false');
                });

                botoesCarrinho.forEach(function (botao) {
                    botao.dataset.variacaoId = slide.dataset.variacaoId;
                    botao.dataset.variacaoRotulo = slide.dataset.variacaoRotulo;
                    botao.dataset.imagem = slide.dataset.imagem;
                });
                rotuloSelecionado.textContent = 'Opção: ' + slide.dataset.variacaoRotulo;
            }

            botoesVariacao.forEach(function (botao) {
                botao.addEventListener('click', function () {
                    const indice = Number(botao.dataset.indiceVariacao);
                    bootstrap.Carousel.getOrCreateInstance(carrosselVariacoes).to(indice);
                    atualizarVariacaoSelecionada(indice);
                });
            });

            carrosselVariacoes.addEventListener('slid.bs.carousel', function (evento) {
                atualizarVariacaoSelecionada(Array.from(evento.target.querySelectorAll('.carousel-item')).indexOf(evento.relatedTarget));
            });
        }
    </script>

</body>

</html>