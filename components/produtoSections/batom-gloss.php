<?php
$produtos = [
    ['nome' => 'Batom Matte 01', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/batom1.jpg', 'descricao' => 'cor intensa e longa duração'],
    ['nome' => 'Gloss Incolor', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/gloss1.jpg', 'descricao' => 'brilho espelhado com ácido hialurônico'],
    ['nome' => 'Batom Tint 02', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/batom2.jpg', 'descricao' => 'efeito natural e duradouro'],
    ['nome' => 'Gloss Rosa 02', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/gloss2.jpg', 'descricao' => 'partículas de brilho e hidratação'],
    ['nome' => 'Batom Nude 03', 'preco' => 'R$24', 'imagem' => '../assets/maquiagens/batom1.jpg', 'descricao' => 'acabamento aveludado'],
    ['nome' => 'Gloss Vermelho 03', 'preco' => 'R$24', 'imagem' => '../assets/maquiagens/gloss1.jpg', 'descricao' => 'efeito vinil nos lábios'],
    ['nome' => 'Batom Nude 03', 'preco' => 'R$24', 'imagem' => '../assets/maquiagens/batom1.jpg', 'descricao' => 'acabamento aveludado'],
    ['nome' => 'Gloss Vermelho 03', 'preco' => 'R$24', 'imagem' => '../assets/maquiagens/gloss1.jpg', 'descricao' => 'efeito vinil nos lábios'],
];

$gruposProdutos = array_chunk($produtos, 4);
?>

<style>
.card-produto-item .card-img-top {
    aspect-ratio: 4 / 3;
    height: auto;
    object-fit: cover;
    border-radius: 13px 13px 0 0;
}
.card-produto-item .card-title { font-size: 1.3rem; }
.card-produto-item .card-text { font-size: 1rem; }
.card-produto-item .preco-produto { font-size: 1.2rem; }
.card-produto-item .btn-comprar { font-size: 15px; }

.container-vitrine .carousel-control-prev,
.container-vitrine .carousel-control-next { width: 5%; }
.container-vitrine .carousel-control-prev { left: -35px; }
.container-vitrine .carousel-control-next { right: -35px; }

@media (max-width: 991.98px) {
    .imagem-produto.banner-lateral { height: 400px !important; }
}

@media (max-width: 767.98px) {
    .container-vitrine { padding: 1rem !important; margin: 0 !important; }
    .container-vitrine .carousel-control-prev { left: 0; }
    .container-vitrine .carousel-control-next { right: 0; }
    .card-produto-item .card-title { font-size: 1rem; }
    .card-produto-item .card-text { font-size: 0.85rem; }
    .card-produto-item .preco-produto { font-size: 1rem; }
    .card-produto-item .btn-comprar { font-size: 12px; padding: 0.4rem; }
}

@media (max-width: 400px) {
    .col-produto { flex: 0 0 100%; max-width: 100%; }
}
    @media (max-width: 1199px){
        .image{
            display: none;
        };
    }
</style>

<main class="overflow-hidden">
    <div class="container-titulo-linhas d-flex align-items-center my-5">
        <div class="linha-lateral flex-grow-1"></div>
        <div class="text-center px-4">
            <h2 class="titulo-principal m-0">Batom e gloss</h2>
            <span class="subtitulo">cor intensa e brilho espelhado em um só duo</span>
        </div>
        <div class="linha-lateral flex-grow-1"></div>
    </div>

    <div class="row align-items-center justify-content-center m-0">
        <!-- 1º FOTO BANNER (Esquerda) -->
        <div class="imagem-produto col-12 col-lg-4 text-center text-lg-end mb-4 mb-lg-0">
            <img src="../assets/maquiagens/batom1.jpg" alt="Banner da Loja" class="banner-lateral img-fluid rounded shadow d-none d-lg-block" style="max-width: 90%; height: 650px; object-fit: cover;">
        </div>

        <!-- 2º VITRINE COM CARROSSEL (Direita) -->
        <div class="col-12 col-xl-5 col-lg-10 col-md-10 col-sm-11 p-4 mx-lg-5 container-vitrine">

            <div id="carrosselBatomGloss" class="carousel slide position-relative" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <?php foreach ($gruposProdutos as $index => $grupo): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="row m-0">
                                <?php foreach ($grupo as $produto): ?>
                                    <div class="col-6 col-produto mt-3 mb-4">
                                        <div class="card card-produto-item h-100 w-100 shadow-sm border-0" style="background-color: #F4D6F8; border-radius: 13px">
                                            <img src="<?php echo $produto['imagem']; ?>" class="card-img-top" alt="<?php echo $produto['nome']; ?>">

                                            <div class="card-body d-flex flex-column p-3">
                                                <h6 class="card-title mb-1 fw-bold"><?php echo $produto['nome']; ?></h6>
                                                <p class="card-text text-muted small"><?php echo $produto['descricao']; ?></p>

                                                <div class="mt-auto">
                                                    <div class="fw-bold mb-2 preco-produto" style="color: darkmagenta"><?php echo $produto['preco']; ?></div>
                                                    <button class="btn btn-sm w-100 fw-bold rounded-pill text-light btn-comprar" style="background-color: #DE97F2;" onclick="window.location.href='produto.php'">COMPRAR</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carrosselBatomGloss" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-secondary rounded-circle p-2" aria-hidden="true" style="background-size: 50%;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carrosselBatomGloss" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-secondary rounded-circle p-2" aria-hidden="true" style="background-size: 50%;"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>

        </div>
    </div>
</main>