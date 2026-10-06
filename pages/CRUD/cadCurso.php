<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar curso de maquiagem</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../styles/styleGeral.css">
    <style>
        :root {
            --primary: #F4D6F8;
            --secundary: #F8F3D6;
            --tertiary: #D5F8F2;
            --primary2: #DE97F2;
            --secundary2: #F2DE97;
            --tertiary2: #63d0d9;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--tertiary) 55%, var(--secundary) 100%);
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 0;
        }

        .form-card {
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .12);
            padding: 2rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--tertiary2);
            box-shadow: 0 0 0 .25rem rgba(99, 208, 217, .25);
        }

        .input-group:focus-within .form-control,
        .input-group:focus-within .input-group-text {
            border-color: var(--tertiary2);
        }

        .input-group-text {
            background-color: var(--tertiary);
            color: #2f6f68;
            font-weight: 600;
        }

        .char-counter {
            font-size: .8rem;
            color: #6c757d;
        }

        .field-hint {
            font-size: .8rem;
            color: #6c757d;
        }

        .btn-save {
            background-color: var(--primary2);
            border-color: var(--primary2);
            color: #fff;
        }

        .btn-save:hover {
            background-color: #c875ec;
            border-color: #c875ec;
            color: #fff;
        }

        .btn-cancel {
            color: #6c757d;
        }
    </style>
</head>

<body>

    <?php include '../../components/header.php' ?>

    <main class="container">
        <div class="form-card mx-auto">
            <div class="mb-4 text-center">
                <h1 class="fs-4 fw-bold mb-1">Cadastrar novo curso de maquiagem</h1>
                <p class="text-secondary mb-0">Preencha as informações do curso</p>
            </div>

            <form method="post" id="courseForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome do curso</label>
                    <input type="text" class="form-control" id="name" name="name"
                        placeholder="Ex: Automaquiagem para o dia a dia">
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Categoria</label>
                    <select class="form-select" id="category" name="category">
                        <option value="" selected disabled>Selecione uma categoria</option>
                        <option>Automaquiagem (dia a dia)</option>
                        <option>Maquiagem social e festas</option>
                        <option>Maquiagem para noivas</option>
                        <option>Maquiagem artística</option>
                        <option>Maquiagem profissional</option>
                        <option>Sobrancelhas e olhos</option>
                        <option>Skincare e preparação de pele</option>
                        <option value="outra">Outra</option>
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="categoryOther" name="categoryOther"
                        placeholder="Especifique a categoria">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="level" class="form-label">Nível</label>
                        <select class="form-select" id="level" name="level">
                            <option value="" selected disabled>Selecione o nível</option>
                            <option>Iniciante</option>
                            <option>Intermediário</option>
                            <option>Avançado</option>
                            <option>Todos os níveis</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="workload" class="form-label">Carga horária</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="workload" name="workload" placeholder="0"
                                min="0" step="1">
                            <span class="input-group-text">horas</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Preço</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control" id="price" name="price" placeholder="0,00"
                                inputmode="decimal">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="prerequisites" class="form-label">Pré-requisitos</label>
                        <input type="text" class="form-control" id="prerequisites" name="prerequisites"
                            placeholder="Ex: Nenhum">
                    </div>
                </div>

                <div class="mb-1">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="500"
                        placeholder="Descreva o curso: técnicas abordadas (ex: esfumado, contorno, cílios postiços, design de sobrancelhas), produtos utilizados, objetivos da aula e para quem é indicado..."></textarea>
                </div>
                <div class="text-end mb-4">
                    <span class="char-counter"><span id="charCount">0</span>/500</span>
                </div>

                <div class="d-flex gap-2">
                    <a href="admin.php" class="btn btn-cancel flex-shrink-0">Cancelar</a>
                    <button type="submit" class="btn btn-save w-100">Salvar curso</button>
                </div>
            </form>
        </div>
    </main>

    <?php include '../../components/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script>
        const categorySelect = document.getElementById('category');
        const categoryOther = document.getElementById('categoryOther');
        categorySelect.addEventListener('change', function() {
            const isOther = categorySelect.value === 'outra';
            categoryOther.classList.toggle('d-none', !isOther);
            if (!isOther) categoryOther.value = '';
        });

        const priceInput = document.getElementById('price');
        priceInput.addEventListener('input', function() {
            let value = priceInput.value.replace(/[^0-9,]/g, '');
            const parts = value.split(',');
            if (parts.length > 2) value = parts[0] + ',' + parts.slice(1).join('');
            priceInput.value = value;
        });

        const description = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        description.addEventListener('input', function() {
            charCount.textContent = description.value.length;
        });
    </script>
</body>

</html>