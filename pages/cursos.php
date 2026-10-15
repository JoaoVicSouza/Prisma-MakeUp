<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma-MakeUp - Cursos</title>
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/StyleCursos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        html {
            scroll-behavior: smooth;
        }

        .secao {
            scroll-margin-top: 100px;
        }

        .prisma-sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #fcf5fd 100%);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(222, 151, 242, 0.15);
            padding: 1.5rem;
            border: 1px solid rgba(222, 151, 242, 0.3);
        }

        .prisma-nav-link {
            color: #555;
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
            color: #DE97F2;
            transition: all 0.3s ease;
        }

        .prisma-nav-link:hover {
            background-color: #F4D6F8;
            color: darkmagenta;
            transform: translateX(6px);
        }

        .prisma-nav-link:hover i {
            color: darkmagenta;
        }

        .prisma-nav-link.active {
            background-color: #DE97F2;
            color: white !important;
            box-shadow: 0 4px 15px rgba(222, 151, 242, 0.4);
        }

        .prisma-nav-link.active i {
            color: white;
        }

        .prisma-sidebar-title {
            color: darkmagenta;
            font-size: 0.85rem;
            letter-spacing: 1.5px;
            font-weight: 700;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main class="container-fluid px-lg-5 my-1 pt-2 flex-grow-1">
        <?php include __DIR__ . '/../components/cursoSections/bannerCurso.php'; ?>

        <div class="row">
            <?php include __DIR__ . '/../components/cursoSections/sidebar.php'; ?>

            <div class="col-12 col-lg-10">
                <button class="btn btn-sm text-light fw-bold rounded-pill d-lg-none mb-4 px-3 py-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarCursos" style="background-color: #DE97F2;">
                    <i class="bi bi-funnel-fill me-1"></i> Filtrar Cursos
                </button>

                <?php include __DIR__ . '/../components/cursoSections/peleCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/olhosCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/sobrancelhasCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/labiosCurso.php'; ?>
                <?php include __DIR__ . '/../components/cursoSections/kitsCurso.php'; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../scripts/scriptCursos.js"></script>
</body>
</html>
