<?php 
require '../../scriptsPHP/conexao.php';

$stmt = $pdo->query("SELECT id, nome, email FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, nome, categoria, preco, estoque FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    $stmt  = $pdo->query("SELECT id, nome, categoria, nivel, preco FROM cursos");
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $cursos = [];
}

// $stmt = $pdo->query("SELECT id, cliente, total, status FROM pedidos");
// $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pedidos = [];

$kpis = [
    ['icon' => 'users',        'val' => count($usuarios), 'label' => 'Clientes'],
    ['icon' => 'box',          'val' => count($produtos), 'label' => 'Produtos'],
    ['icon' => 'shopping-cart','val' => count($pedidos),  'label' => 'Pedidos'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prisma Makeup - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="../../styles/styleAdmin.css">
  <link rel="stylesheet" href="../../styles/styleGeral.css">
</head>

<body>

  <?php include '../../components/header.php'; 
    if($_SESSION['admin'] !== 1){
      header('Location: ../../index.php');
      exit;  
    }
  ?>

  <div class="admin-wrapper">
    <aside class="sidebar">
      <div class="sidebar-logo">
        <a href="#">Prisma <span>Makeup</span></a>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section">
          <p class="nav-section-label">Visão Geral</p>
          <button class="nav-item active" onclick="goTo('dashboard',this)"><i data-feather="grid"></i>
            Dashboard</button>
        </div>
        <div class="nav-section">
          <p class="nav-section-label">Comércio</p>
          <button class="nav-item" onclick="goTo('produtos',this)"><i data-feather="box"></i> Produtos</button>
          <button class="nav-item" onclick="goTo('pedidos',this)"><i data-feather="shopping-cart"></i> Pedidos <span
              class="nav-badge" id="pendingBadge">0</span></button>
        </div>
        <div class="nav-section">
          <p class="nav-section-label">Plataforma</p>
          <button class="nav-item" onclick="goTo('cursos',this)"><i data-feather="play-circle"></i> Tutoriais</button>
          <button class="nav-item" onclick="goTo('usuarios',this)"><i data-feather="users"></i> Usuárias</button>
        </div>
      </nav>
      <div class="sidebar-footer">
        <button class="logout-btn" onclick="logout()"><i data-feather="log-out"></i> Encerrar sessão</button>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
      <div class="topbar">
        <div class="topbar-left">
          <p class="page-title" id="pageTitle">Dashboard</p>
        </div>
        <div class="topbar-right">
        </div>
      </div>

      <div class="content">
        <!-- DASHBOARD -->
        <div class="panel active" id="panel-dashboard">
          <div class="kpi-grid">
            <?php foreach ($kpis as $k): ?>
              <div class="kpi">
                <div class="kpi-icon"><i data-feather="<?= $k['icon'] ?>"></i></div>
                <div>
                  <div class="kpi-val"><?= $k['val'] ?></div>
                  <div class="kpi-label"><?= $k['label'] ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="dash-grid">
            <div class="card-admin">
              <div class="card-head">
                <span class="card-title">Vendas da Semana</span>
              </div>
              <div class="mini-chart">
                Sem dados suficientes para gerar o gráfico.
              </div>
            </div>
            <div class="card-admin">
              <div class="card-head">
                <span class="card-title">Atividade Recente</span>
              </div>
              <div style="font-size: 0.9rem; color: #7a688a;">
                O log de atividades está vazio.
              </div>
            </div>
          </div>
        </div>

        <!-- PRODUTOS -->
        <div class="panel" id="panel-produtos">
          <div class="table-toolbar">
            <div class="search-box">
              <i data-feather="search"></i>
              <input type="text" id="prodSearch" placeholder="Pesquisar produto…"
                oninput="filterTable('prodTable',this.value)" />
            </div>
            <button class="btn-primary-custom" onclick="window.location.href='cadProd.php'"><i
                data-feather="plus"></i> Novo Produto</button>
          </div>
          <div class="card-admin" style="padding:0; overflow:hidden;">
            <table class="data-table" id="prodTable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Categoria</th>
                  <th>Preço</th>
                  <th>Estoque</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="prodBody">
                <?php if (empty($produtos)): ?>
                  <tr>
                    <td colspan="5" style="text-align:center; color:#7a688a; padding: 30px;">Nenhum produto cadastrado na base.</td>
                  </tr>
                <?php else: foreach ($produtos as $p): ?>
                  <tr>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                    <td>R$ <?= htmlspecialchars($p['preco']) ?></td>
                    <td><?= htmlspecialchars($p['estoque']) ?></td>
                    <td>
                      <a class="action-btn" href="mod.php?tipo=produto&id=<?= $p['id'] ?>" title="Editar"><i data-feather="edit-2"></i></a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PEDIDOS -->
        <div class="panel" id="panel-pedidos">
          <div class="table-toolbar">
            <div class="search-box">
              <i data-feather="search"></i>
              <input type="text" id="orderSearch" placeholder="Buscar ID ou cliente…"
                oninput="filterTable('orderTable',this.value)" />
            </div>
          </div>
          <div class="card-admin" style="padding:0; overflow:hidden;">
            <table class="data-table" id="orderTable">
              <thead>
                <tr>
                  <th>Nº Pedido</th>
                  <th>Cliente</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="orderBody">
                <?php if (empty($pedidos)): ?>
                  <tr>
                    <td colspan="5" style="text-align:center; color:#7a688a; padding: 30px;">Não há registros de pedidos.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- USUÁRIOS -->
        <div class="panel" id="panel-usuarios">
          <div class="table-toolbar">
            <div class="search-box">
              <i data-feather="search"></i>
              <input type="text" id="userSearch" placeholder="Procurar usuário…"
                oninput="filterTable('userTable',this.value)" />
            </div>
          </div>
          <div class="card-admin" style="padding:0; overflow:hidden;">
            <table class="data-table" id="userTable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>E-mail</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="userBody">
                <?php if (empty($usuarios)): ?>
                  <tr>
                    <td colspan="3" style="text-align:center; color:#7a688a; padding: 30px;">Nenhuma conta de cliente
                      registrada.</td>
                  </tr>
                <?php else: foreach ($usuarios as $u): ?>
                  <tr>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                      <a class="action-btn" href="mod.php?tipo=usuario&id=<?= $u['id'] ?>" title="Editar"><i data-feather="edit-2"></i></a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TUTORIAIS -->
        <div class="panel" id="panel-cursos">
          <div class="table-toolbar">
            <div class="search-box">
              <i data-feather="search"></i>
              <input type="text" id="cursoSearch" placeholder="Pesquisar curso…"
                oninput="filterTable('cursoTable',this.value)" />
            </div>
            <button class="btn-primary-custom" onclick="window.location.href='cadCurso.php'"><i
                data-feather="upload-cloud"></i> Adicionar Mídia</button>
          </div>
          <div class="card-admin" style="padding:0; overflow:hidden;">
            <table class="data-table" id="cursoTable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Categoria</th>
                  <th>Nível</th>
                  <th>Preço</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($cursos)): ?>
                  <tr>
                    <td colspan="5" style="text-align:center; color:#7a688a; padding: 30px;">A biblioteca de tutoriais está vazia no momento.</td>
                  </tr>
                <?php else: foreach ($cursos as $c): ?>
                  <tr>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['categoria']) ?></td>
                    <td><?= htmlspecialchars($c['nivel'] ?? '—') ?></td>
                    <td>R$ <?= htmlspecialchars($c['preco'] ?? '—') ?></td>
                    <td>
                      <a class="action-btn" href="mod.php?tipo=curso&id=<?= $c['id'] ?>" title="Editar"><i data-feather="edit-2"></i></a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../../components/footer.php'; ?>

  <div class="toast-wrap" id="toastWrap"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../scripts/scriptGeral.js"></script>
  <script>
    feather.replace();

    function goTo(panel, btn) {
      document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
      document.getElementById('panel-' + panel).classList.add('active');
      if (btn) btn.classList.add('active');

      const titles = {
        dashboard: 'Visão Geral',
        produtos: 'Produtos',
        pedidos: 'Pedidos',
        usuarios: 'Clientes',
        cursos: 'Cursos'
      };

      document.getElementById('pageTitle').textContent = titles[panel] || panel;
    }

    function filterTable(tableId, term) {
      const rows = document.querySelectorAll(`#${tableId} tbody tr`);
      rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(term.toLowerCase()) ? '' : 'none';
      });
    }

    function showToast(iconName, msg, cls) {
      const wrap = document.getElementById('toastWrap');
      const t = document.createElement('div');
      t.className = `toast ${cls}`;
      t.innerHTML = `<i data-feather="${iconName}"></i> <span>${msg}</span>`;
      wrap.appendChild(t);
      feather.replace();

      setTimeout(() => {
        t.classList.add('removing');
        setTimeout(() => t.remove(), 300);
      }, 3500);
    }

    function logout() { window.location.href = '../login.php'; }

    <?php if (isset($form_success) && $form_success): ?>
      showToast('check-circle', 'Produto registrado com sucesso.', 'ok');
    <?php endif; ?>
  </script>
</body>

</html>