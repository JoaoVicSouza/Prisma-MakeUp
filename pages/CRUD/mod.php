<?php
require '../../scriptsPHP/conexao.php';

$tipo = $_GET['tipo'] ?? '';
$id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$tiposValidos = ['produto', 'usuario', 'curso'];
if (!in_array($tipo, $tiposValidos) || !$id) {
  header('Location: admin.php');
  exit;
}

$item = null;

if ($tipo === 'produto') {
  $stmt = $pdo->prepare("SELECT id, nome, categoria, descricao, preco, estoque, conteudo, validade, indicacao, cuidados, informacoes FROM produtos WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($tipo === 'usuario') {
  $stmt = $pdo->prepare("SELECT id, nome, email, admin FROM usuarios WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($tipo === 'curso') {
  $stmt = $pdo->prepare("SELECT id, nome, categoria, nivel, carga_horaria, preco, prerequisitos, descricao FROM cursos WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$item) {
  header('Location: admin.php');
  exit;
}

$titulos = ['produto' => 'Editar Produto', 'usuario' => 'Editar Usuário', 'curso' => 'Editar Curso'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulos[$tipo] ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../styles/styleGeral.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #F4D6F8 0%, #D5F8F2 55%, #F8F3D6 100%);
      padding: 0;
    }

    .form-card {
      width: 100%;
      max-width: 560px;
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 1rem 3rem rgba(0, 0, 0, .12);
      padding: 2rem;
      margin: 2rem;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #63d0d9;
      box-shadow: 0 0 0 .25rem rgba(99, 208, 217, .25);
    }

    .btn-save {
      background-color: #DE97F2;
      border-color: #DE97F2;
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
        <h1 class="fs-4 fw-bold mb-1"><?= $titulos[$tipo] ?></h1>
        <p class="text-secondary mb-0">Altere os campos desejados e salve</p>
      </div>

      <?php if ($tipo === 'produto'): ?>
        <form method="post" action="../../scriptsPHP/editProd.php">
          <input type="hidden" name="id" value="<?= $item['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Nome do produto</label>
            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($item['nome']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select class="form-select" name="categoria">
              <?php foreach (['Preparação de pele', 'Pele', 'Olhos', 'Sobrancelhas', 'Lábios', 'Acessórios', 'Kits', 'Outra'] as $cat): ?>
                <option <?= $item['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Preço</label>
              <div class="input-group">
                <span class="input-group-text" style="background:#D5F8F2;color:#2f6f68;font-weight:600;">R$</span>
                <input type="text" class="form-control" name="preco" value="<?= htmlspecialchars($item['preco']) ?>" required>
              </div>
            </div>
            <div class="col-6">
              <label class="form-label">Estoque</label>
              <input type="number" class="form-control" name="estoque" value="<?= htmlspecialchars($item['estoque']) ?>" min="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Conteúdo</label>
            <input type="text" class="form-control" name="conteudo" value="<?= htmlspecialchars($item['conteudo'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Validade</label>
            <input type="text" class="form-control" name="validade" value="<?= htmlspecialchars($item['validade'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" rows="3"><?= htmlspecialchars($item['descricao'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Indicação</label>
            <textarea class="form-control" name="indicacao" rows="3"><?= htmlspecialchars($item['indicacao'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Cuidados</label>
            <textarea class="form-control" name="cuidados" rows="3"><?= htmlspecialchars($item['cuidados'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Informações Técnicas</label>
            <textarea class="form-control" name="informacoes" rows="3"><?= htmlspecialchars($item['informacoes'] ?? '') ?></textarea>
          </div>
          <div class="d-flex gap-2">
            <a href="admin.php" class="btn btn-cancel flex-shrink-0">Cancelar</a>
            <button type="submit" class="btn btn-save w-100">Salvar alterações</button>
          </div>
        </form>

      <?php elseif ($tipo === 'usuario'): ?>
        <form method="post" action="../../scriptsPHP/editUsuario.php">
          <input type="hidden" name="id" value="<?= $item['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($item['nome']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($item['email']) ?>" required>
          </div>
          <div class="form-check mb-3">
            <!-- O value="1" só é enviado no $_POST se o checkbox for marcado -->
            <input class="form-check-input" type="checkbox" name="admin" id="admin" value="1" <?php echo ($item['admin'] == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="admin">
              Privilégios de Administrador
            </label>
          </div>

          <div class="d-flex gap-2">
            <a href="admin.php" class="btn btn-cancel flex-shrink-0">Cancelar</a>
            <button type="submit" class="btn btn-save w-100">Salvar alterações</button>
          </div>
        </form>

      <?php elseif ($tipo === 'curso'): ?>
        <form method="post" action="../../scriptsPHP/editCurso.php">
          <input type="hidden" name="id" value="<?= $item['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Nome do curso</label>
            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($item['nome']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select class="form-select" name="categoria">
              <?php foreach (['Automaquiagem (dia a dia)', 'Maquiagem social e festas', 'Maquiagem para noivas', 'Maquiagem artística', 'Maquiagem profissional', 'Sobrancelhas e olhos', 'Skincare e preparação de pele', 'Outra'] as $cat): ?>
                <option <?= ($item['categoria'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Nível</label>
              <select class="form-select" name="nivel">
                <?php foreach (['Iniciante', 'Intermediário', 'Avançado', 'Todos os níveis'] as $nv): ?>
                  <option <?= ($item['nivel'] ?? '') === $nv ? 'selected' : '' ?>><?= $nv ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Carga horária</label>
              <div class="input-group">
                <input type="number" class="form-control" name="carga_horaria" value="<?= htmlspecialchars($item['carga_horaria'] ?? '') ?>" min="0">
                <span class="input-group-text" style="background:#D5F8F2;color:#2f6f68;font-weight:600;">h</span>
              </div>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Preço</label>
              <div class="input-group">
                <span class="input-group-text" style="background:#D5F8F2;color:#2f6f68;font-weight:600;">R$</span>
                <input type="text" class="form-control" name="preco" value="<?= htmlspecialchars($item['preco'] ?? '') ?>">
              </div>
            </div>
            <div class="col-6">
              <label class="form-label">Pré-requisitos</label>
              <input type="text" class="form-control" name="prerequisitos" value="<?= htmlspecialchars($item['prerequisitos'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" rows="4"><?= htmlspecialchars($item['descricao'] ?? '') ?></textarea>
          </div>
          <div class="d-flex gap-2">
            <a href="admin.php" class="btn btn-cancel flex-shrink-0">Cancelar</a>
            <button type="submit" class="btn btn-save w-100">Salvar alterações</button>
          </div>
        </form>
      <?php endif; ?>

    </div>
  </main>
  <?php include '../../components/footer.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>