<?php
$produtos = [
    ['nome' => 'Corretivo1', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/corretivo1.jpg', 'descricao' => 'maquiagem muito bacana'],
    ['nome' => 'corretivo2', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/corretivo2.jpg', 'descricao' => 'maquiagem muito bacana'],
    ['nome' => 'corretivo3', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/corretivo3.jpg', 'descricao' => 'maquiagem muito bacana'],
    ['nome' => 'corretivo4', 'preco' => 'R$22', 'imagem' => '../assets/maquiagens/corretivo4.jpg', 'descricao' => 'maquiagem muito bacana'],
    ['nome' => 'corretivo5', 'preco' => 'R$25', 'imagem' => '../assets/maquiagens/corretivo1.jpg', 'descricao' => 'alta cobertura e iluminação'],
    ['nome' => 'corretivo6', 'preco' => 'R$25', 'imagem' => '../assets/maquiagens/corretivo2.jpg', 'descricao' => 'toque aveludado matte'],
    ['nome' => 'corretivo7', 'preco' => 'R$25', 'imagem' => '../assets/maquiagens/corretivo1.jpg', 'descricao' => 'alta cobertura e iluminação'],
    ['nome' => 'corretivo8', 'preco' => 'R$25', 'imagem' => '../assets/maquiagens/corretivo2.jpg', 'descricao' => 'toque aveludado matte'],
];

$gruposProdutos = array_chunk($produtos, 4);
?>

<style>
    @media (max-width: 1199px){
        .image{
            display: none;
        };
    }
</style>

<main class="overflow-hidden">
    <div class="container-titulo-linhas d-flex align-items-center mt-5">
        <div class="linha-lateral flex-grow-1"></div>
        <div class="text-center px-4">
            <h2 class="titulo-principal m-0">Corretivos</h2>
            <span class="subtitulo">adeus olheiras, olá pele perfeita</span>
        </div>
        <div class="linha-lateral flex-grow-1"></div>
    </div>  

    <div class="row align-items-center justify-content-center m-0 offset-lg-1">
        <div class="col-12 col-xl-5 col-lg-10 col-md-10 col-sm-11 p-4 mx-lg-5 container-vitrine">

            <div id="carrosselCorretivos" class="carousel slide position-relative" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <?php foreach ($gruposProdutos as $index => $grupo): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="row">
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

                <button class="carousel-control-prev" type="button" data-bs-target="#carrosselCorretivos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-secondary rounded-circle p-2" aria-hidden="true" style="background-size: 50%;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carrosselCorretivos" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-secondary rounded-circle p-2" aria-hidden="true" style="background-size: 50%;"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>

        </div>

        <div class="col-12 col-lg-4 col-md-0 col-sm-0 text-center text-lg-end mb-4 mb-lg-0 ms-lg-5 image">
            <img src="../assets/maquiagens/blush2.jpg" alt="Banner da Loja" class="imagem-produto banner-lateral img-fluid rounded shadow d-none d-lg-block" style="max-width: 90%; height: 650px; object-fit: fill;">
        </div>
    </div>
</main>