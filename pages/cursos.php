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
    <style>
        html {
            scroll-behavior: smooth;
        }

        .secao {
            scroll-margin-top: 100px;
        }

        .cursos-page {
            position: relative;
        }

        .prisma-sidebar {
            background-color: var(--bege);
            border-radius: 24px;
            box-shadow: 0 10px 30px color-mix(in srgb, var(--roxo) 15%, transparent);
            padding: 1.5rem;
            border: 1px solid color-mix(in srgb, var(--roxo) 30%, transparent);
        }

        .prisma-nav-link {
            color: var(--roxo);
            text-decoration: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .prisma-nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            color: var(--roxo);
            transition: all 0.3s ease;
        }

        .prisma-nav-link:hover {
            background-color: var(--verde);
            color: var(--roxo);
            transform: translateX(6px);
        }

        .prisma-nav-link:hover i {
            color: var(--roxo);
        }

        .prisma-nav-link.active {
            background-color: var(--roxo);
            color: var(--bege) !important;
            box-shadow: 0 4px 15px color-mix(in srgb, var(--roxo) 40%, transparent);
        }

        .prisma-nav-link.active i {
            color: var(--bege);
        }

        .prisma-sidebar-title {
            color: var(--roxo);
            font-size: 0.85rem;
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        .cursos-filter {
            color: #000;
        }
        
        
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-bege">
    <?php include __DIR__ . '/../components/header.php'; ?>
    <?php include __DIR__ . '/../components/cursoSections/bannerCurso.php'; ?>

    <main class="cursos-page container-fluid px-lg-5 mt-1 mb-0 flex-grow-1">
        <div class="row">
            <?php include __DIR__ . '/../components/cursoSections/sidebar.php'; ?>

            <div class="col-12 col-lg-10">
                <button class="btn btn-sm bg-roxo cursos-filter fw-bold rounded-pill d-lg-none mb-4 px-3 py-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarCursos">
                    <i class="bi bi-funnel-fill me-1"></i> Filtrar Cursos
                </button>

                <?php include __DIR__ . '/../components/cursoSections/peleCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/olhosCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/sobrancelhasCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/labiosCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/kitsCurso.php'; ?>
            </div>
        </div>
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
