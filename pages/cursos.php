<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/styleCursos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include '../components/header.php' ?>

    <?php
    // Simulando os cursos do banco de dados
    $cursos = [
        [
            'titulo' => 'Automaquiagem: Pele Perfeita e Contorno do Zero',
            'thumb' => '../assets/cursos/capa-pele.jpg', // Troque pelas suas imagens
            'instrutor' => 'Aline Costa',
            'instrutor_foto' => '../assets/cursos/avatar1.jpg',
            'aulas' => '12',
            'nota' => '4.9',
            'alunos' => '1.2k'
        ],
        [
            'titulo' => 'Olhos Esfumados: Técnicas de Transição de Cores',
            'thumb' => '../assets/cursos/capa-olhos.jpg',
            'instrutor' => 'Camila Silva',
            'instrutor_foto' => '../assets/cursos/avatar2.jpg',
            'aulas' => '8',
            'nota' => '4.8',
            'alunos' => '850'
        ],
        [
            'titulo' => 'Skincare Pré-Make: Preparação Que Faz a Make Durar',
            'thumb' => '../assets/cursos/capa-skincare.jpg',
            'instrutor' => 'Dra. Beatriz Lima',
            'instrutor_foto' => '../assets/cursos/avatar3.jpg',
            'aulas' => '5',
            'nota' => '5.0',
            'alunos' => '2.1k'
        ],
        [
            'titulo' => 'Maquiagem Artística e Colorimetria Avançada',
            'thumb' => '../assets/cursos/capa-artistica.jpg',
            'instrutor' => 'Lucas MakeUp',
            'instrutor_foto' => '../assets/cursos/avatar4.jpg',
            'aulas' => '15',
            'nota' => '4.7',
            'alunos' => '500'
        ],
        [
            'titulo' => 'Maquiagem Artística e Colorimetria Avançada',
            'thumb' => '../assets/cursos/capa-artistica.jpg',
            'instrutor' => 'Lucas MakeUp',
            'instrutor_foto' => '../assets/cursos/avatar4.jpg',
            'aulas' => '15',
            'nota' => '4.7',
            'alunos' => '500'
        ],
        [
            'titulo' => 'Maquiagem Artística e Colorimetria Avançada',
            'thumb' => '../assets/cursos/capa-artistica.jpg',
            'instrutor' => 'Lucas MakeUp',
            'instrutor_foto' => '../assets/cursos/avatar4.jpg',
            'aulas' => '15',
            'nota' => '4.7',
            'alunos' => '500'
        ],
        [
            'titulo' => 'Maquiagem Artística e Colorimetria Avançada',
            'thumb' => '../assets/cursos/capa-artistica.jpg',
            'instrutor' => 'Lucas MakeUp',
            'instrutor_foto' => '../assets/cursos/avatar4.jpg',
            'aulas' => '15',
            'nota' => '4.7',
            'alunos' => '500'
        ],
        [
            'titulo' => 'Maquiagem Artística e Colorimetria Avançada',
            'thumb' => '../assets/cursos/capa-artistica.jpg',
            'instrutor' => 'Lucas MakeUp',
            'instrutor_foto' => '../assets/cursos/avatar4.jpg',
            'aulas' => '15',
            'nota' => '4.7',
            'alunos' => '500'
        ],
        [
            'titulo' => 'Maquiagem Artística e Colorimetria Avançada',
            'thumb' => '../assets/cursos/capa-artistica.jpg',
            'instrutor' => 'Lucas MakeUp',
            'instrutor_foto' => '../assets/cursos/avatar4.jpg',
            'aulas' => '15',
            'nota' => '4.7',
            'alunos' => '500'
        ]
    ];
    ?>

    <!-- Adicionado flex-grow-1 para empurrar o footer para baixo -->
    <main class="container-fluid px-lg-5 my-1 pt-2 flex-grow-1">
        
        <!-- Título da Página com as Linhas da Prisma MakeUp -->
        <div class="container-titulo-linhas d-flex align-items-center mb-5">
            <div class="linha-lateral flex-grow-1"></div>
            <div class="text-center px-4">
                <h2 class="titulo-principal m-0">Aprenda com a Prisma</h2>
                <span class="subtitulo">cursos exclusivos para aprimorar suas técnicas</span>
            </div>
            <div class="linha-lateral flex-grow-1"></div>
        </div>

        <div class="row">
            
            <!-- 1. ABA LATERAL (Gaveta no celular / Barra fixa no computador) -->
            <nav id="sidebarCursos" class="col-12 col-lg-2 offcanvas-lg offcanvas-start border-end-lg pe-lg-4 mb-4 mb-lg-0" style="background-color: #DE97F2;">
                
                <!-- Cabeçalho do Menu Mobile -->
                <div class="offcanvas-header border-bottom mb-3 d-lg-none">
                    <h5 class="offcanvas-title fw-bold" style="color: darkmagenta;">Categorias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarCursos"></button>
                </div>

                <!-- Corpo da Aba Lateral -->
                <div class="offcanvas-body d-flex flex-column">
                    <span class="fw-bold text-uppercase text-muted small mb-3">Filtrar por</span>
                    
                    <ul class="nav nav-pills flex-column mb-auto gap-1">
                        <li class="nav-item">
                            <a href="#" class="nav-link active rounded-3"><i class="bi bi-grid-fill me-2"></i> Todos os Cursos</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark rounded-3"><i class="bi bi-person-hearts me-2"></i> Pele e Contorno</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark rounded-3"><i class="bi bi-eye-fill me-2"></i> Olhos e Delineado</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark rounded-3"><i class="bi bi-stars me-2"></i> Skincare</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- 2. ÁREA PRINCIPAL (Vitrine de Cursos) -->
            <div class="col-12 col-lg-10">
                
                <!-- Botão de abrir menu no celular (Soma em telas grandes) -->
                <button class="btn btn-sm text-light fw-bold rounded-pill d-lg-none mb-4 px-3 py-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarCursos" style="background-color: #DE97F2;">
                    <i class="bi bi-funnel-fill me-1"></i> Filtrar Cursos
                </button>

                <!-- GRID ESTILO YOUTUBE -->
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4 mt-4">
                    <?php foreach ($cursos as $curso): ?>
                    <div class="col">
                        <div class="card border-0 bg-transparent h-100 curso-youtube-card">
                            
                            <!-- Miniatura 16:9 -->
                            <div class="position-relative overflow-hidden rounded-4 mb-3" style="background-color: #f8f9fa;">
                                <div class="ratio ratio-16x9">
                                    <!-- Caso a imagem não carregue, adicionei um fundo cinza claro de fallback -->
                                    <img src="<?php echo $curso['thumb']; ?>" class="object-fit-cover rounded-4" alt="Capa do curso" onerror="this.src='https://placehold.co/600x338/F4D6F8/DE97F2?text=MakeUp'">
                                </div>
                                <span class="position-absolute bottom-0 end-0 text-white px-2 py-1 m-2 rounded-2 fw-semibold" style="font-size: 0.75rem; background-color: rgba(0, 0, 0, 0.75);">
                                    <?php echo $curso['aulas']; ?> aulas
                                </span>
                            </div>

                            <!-- Informações do Curso -->
                            <div class="d-flex gap-3 px-1">
                                <!-- Avatar -->
                                <img src="<?php echo $curso['instrutor_foto']; ?>" class="rounded-circle object-fit-cover shadow-sm" width="40" height="40" alt="Instrutor" onerror="this.src='https://placehold.co/40x40/ccc/fff?text=Avatar'">
                                
                                <!-- Textos -->
                                <div>
                                    <h6 class="fw-bold mb-1 titulo-curso" style="font-size: 1rem; line-height: 1.3;">
                                        <?php echo $curso['titulo']; ?>
                                    </h6>
                                    <p class="text-secondary small mb-0 mt-1"><?php echo $curso['instrutor']; ?></p>
                                    <p class="text-secondary small mb-0">
                                        <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i> <?php echo $curso['nota']; ?> • <?php echo $curso['alunos']; ?> alunos
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </main>
<?php include '../components/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>