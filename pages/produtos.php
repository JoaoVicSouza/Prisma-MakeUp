<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisma-MakeUp - Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/styleProdutos.css">
    <style>
        .container-titulo-linhas {
            width: 71.8dvw;
            margin: 0 auto;
        }

        .linha-lateral {
            height: 1px;
            background-color: var(--roxo);
        }

        .titulo-principal {
            color: var(--azul);
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .subtitulo {
            color: var(--roxo);
            font-size: 0.85rem;
        }
    </style>
</head>

<body class="bg-bege">

    <?php include '../components/header.php' ?>
    <?php include '../components/produtoSections/banner.php' ?>
    <?php include '../components/produtoSections/vitrineCategorias.php' ?>
    <?php include '../components/footer.php' ?>

    <script src="../scripts/scriptGeral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>