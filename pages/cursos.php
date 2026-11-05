<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma-MakeUp - Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/StyleCursos.css">
    <link rel="stylesheet" href="../styles/styleRespCursos.css">
    <link rel="icon" href="../assets/logo-icon.png" type="image/png">
</head>
<body class="d-flex flex-column min-vh-100 bg-bege">
    <?php include __DIR__ . '/../components/header.php'; ?>
    <?php include __DIR__ . '/../components/cursoSections/bannerCurso.php'; ?>

    <main class="cursos-page container-fluid px-lg-5 mt-1 mb-0 flex-grow-1">
        <div class="cursos-catalogo">
            <form class="cursos-filtros" id="filtrosCursos" role="search" aria-label="Filtrar cursos">
                <div class="cursos-filtros-campos">
                    <div class="cursos-filtro">
                        <label for="buscaCursos">Buscar curso</label>
                        <div class="cursos-busca">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <input
                                type="search"
                                id="buscaCursos"
                                name="busca"
                                placeholder="Ex.: maquiagem para pele"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="cursos-filtro">
                        <label for="categoriaCursos">Categoria</label>
                        <select id="categoriaCursos" name="categoria">
                            <option value="todos">Todas as categorias</option>
                            <option value="pele">Pele</option>
                            <option value="olhos">Olhos</option>
                            <option value="sobrancelhas">Sobrancelhas</option>
                            <option value="labios">Lábios</option>
                            <option value="kits">Kits</option>
                        </select>
                    </div>
                    <div class="cursos-filtro">
                        <label for="nivelCursos">Nível</label>
                        <select id="nivelCursos" name="nivel">
                            <option value="todos">Todos os níveis</option>
                            <option value="iniciante">Iniciante</option>
                            <option value="intermediario">Intermediário</option>
                            <option value="avancado">Avançado</option>
                        </select>
                    </div>
                    <button class="cursos-limpar" type="reset">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                        Limpar filtros
                    </button>
                </div>
                <p class="cursos-resultado" id="resultadoCursos" aria-live="polite"></p>
            </form>

            <div id="listaCursos">
                <?php include __DIR__ . '/../components/cursoSections/peleCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/olhosCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/sobrancelhasCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/labiosCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/kitsCurso.php'; ?>
            </div>
        </div>
        <p class="cursos-sem-resultado" id="semResultadoCursos" hidden>
            Nenhum curso encontrado com esses filtros. Tente alterar sua busca.
        </p>
        <div class="cursos-wave" aria-hidden="true">
            <svg viewBox="0 0 1440 64" preserveAspectRatio="none">
                <path class="cursos-wave-desktop" d="M0 22C60 22 60 48 120 48S180 22 240 22 300 48 360 48 420 22 480 22 540 48 600 48 660 22 720 22 780 48 840 48 900 22 960 22 1020 48 1080 48 1140 22 1200 22 1260 48 1320 48 1380 22 1440 22V64H0Z" fill="currentColor"/>
                <path class="cursos-wave-mobile" d="M0 22C180 22 180 48 360 48S540 22 720 22 900 48 1080 48 1260 22 1440 22V64H0Z" fill="currentColor"/>
            </svg>
        </div>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/scriptGeral.js"></script>
    <script src="../scripts/scriptCursos.js"></script>
</body>
</html>
