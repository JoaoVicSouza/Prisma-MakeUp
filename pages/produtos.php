<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styleGeral.css">
    <link rel="stylesheet" href="../styles/styleProdutos.css">
    <style>
        /* Estilo para a caixa geral não ficar grudada nos cantos do monitor */
        .container-titulo-linhas {
            width: 71.8dvw;
            /* Controla o tamanho máximo das linhas */
            margin: 0 auto;
            /* Centraliza na tela */
        }

        /* A mágica da linha */
        .linha-lateral {
            height: 1px;
            /* Espessura da linha */
            background-color: #63d0d9;
            /* Cor da linha (azul escuro igual da foto) */
        }

        /* Estilo do título principal */
        .titulo-principal {
            color: #63d0d9;
            /* Azul escuro */
            font-weight: 500;
            /* Deixa a fonte média */
            letter-spacing: 2px;
            /* Espaço elegante entre as letras */
            text-transform: uppercase;
        }

        /* Estilo do subtítulo */
        .subtitulo {
            color: #DE97F2;
            /* Cinza padrão */
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    
    <?php include '../components/header.php' ?>
    <?php include '../components/produtoSections/banner.php' ?>
    <?php include '../components/produtoSections/corretivo.php' ?>
    <?php include '../components/produtoSections/batom-gloss.php' ?>
    <?php include '../components/produtoSections/rimel.php' ?>
    <?php include '../components/produtoSections/po-facial.php' ?>
    <?php include '../components/produtoSections/blush.php' ?>
    <?php include '../components/footer.php' ?>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>