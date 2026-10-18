<?php
require_once '../../scriptsPHP/adminAuth.php';
requireAdmin('../../index.php');
require '../../scriptsPHP/conexao.php';

$stmt = $pdo->query("SELECT id, nome, email FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, nome, categoria, preco, estoque FROM produtos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT c.id, c.nome, c.nivel, c.cargaH,
                            p.nome AS produto_nome
                     FROM cursos AS c
                     LEFT JOIN produtos AS p ON p.id = c.id_produto
                     ORDER BY c.id");
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);



$stmt = $pdo->query(
  "SELECT id, nomeDest AS cliente, valorTotal AS total, statusPag, statusPedido AS status, dataHora
     FROM pedidos ORDER BY dataHora DESC, id DESC"
);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$atividadeRecente = array_slice($pedidos, 0, 5);

$stmt = $pdo->query(
  "SELECT DATE(dataHora) AS dia, SUM(valorTotal) AS total
     FROM pedidos
     WHERE statusPag = 'Pago'
       AND dataHora >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
       AND dataHora < DATE_ADD(CURDATE(), INTERVAL 1 DAY)
     GROUP BY DATE(dataHora)"
);
$totaisPorDia = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $venda) {
  $totaisPorDia[$venda['dia']] = (int) $venda['total'];
}

$diasSemana = ['domingo', 'segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado'];
$hoje = new DateTimeImmutable('today');
$vendasSemana = [];
for ($diasAtras = 6; $diasAtras >= 0; $diasAtras--) {
  $data = $hoje->modify("-{$diasAtras} days");
  $dia = $data->format('Y-m-d');
  $vendasSemana[] = [
    'label' => ucfirst($diasSemana[(int) $data->format('w')]),
    'data' => $dia,
    'total' => $totaisPorDia[$dia] ?? 0,
  ];
}
$maiorVendaSemana = max(array_column($vendasSemana, 'total'));

$kpis = [
  ['icon' => 'users',        'val' => count($usuarios), 'label' => 'Clientes'],
  ['icon' => 'box',          'val' => count($produtos), 'label' => 'Produtos'],
  ['icon' => 'shopping-cart', 'val' => count($pedidos),  'label' => 'Pedidos'],
  ['icon' => 'play-circle',   'val' => count($cursos),   'label' => 'Tutoriais']
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

  <?php include '../../components/header.php'; ?>

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
          <button class="nav-item" onclick="goTo('pedidos',this)"><i data-feather="shopping-cart"></i> Pedidos</button>
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
        <div>
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
                <span class="card-title">Vendas confirmadas — últimos 7 dias</span>
              </div>
              <div class="mini-chart sales-chart" role="img" aria-label="Vendas pagas por dia nos últimos sete dias">
                <?php foreach ($vendasSemana as $venda):
                  $altura = $maiorVendaSemana > 0 ? ($venda['total'] / $maiorVendaSemana) * 100 : 0;
                ?>
                  <div class="sales-day" title="<?= htmlspecialchars($venda['label'] . ': R$ ' . number_format($venda['total'] / 100, 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?>">
                    <span class="sales-value">R$ <?= number_format($venda['total'] / 100, 2, ',', '.') ?></span>
                    <div class="sales-bar-wrap">
                      <span class="sales-bar" style="height: <?= $altura ?>%"></span>
                    </div>
                    <span class="sales-label"><?= htmlspecialchars($venda['label'], ENT_QUOTES, 'UTF-8') ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="card-admin">
              <div class="card-head">
                <span class="card-title">Pedidos recentes</span>
              </div>
              <div class="recent-activity">
                <?php if (empty($atividadeRecente)): ?>
                  <p class="activity-empty">Nenhum pedido registrado ainda.</p>
                  <?php else: foreach ($atividadeRecente as $atividade):
                    $timestamp = strtotime((string) $atividade['dataHora']);
                  ?>
                    <a class="activity-item" href="mod.php?tipo=pedido&id=<?= (int) $atividade['id'] ?>">
                      <span class="activity-icon"><i data-feather="shopping-bag"></i></span>
                      <span class="activity-details">
                        <strong>Pedido #<?= (int) $atividade['id'] ?></strong>
                        <span><?= htmlspecialchars($atividade['cliente'], ENT_QUOTES, 'UTF-8') ?></span>
                        <small>
                          <?= htmlspecialchars($atividade['status'], ENT_QUOTES, 'UTF-8') ?>
                          <?php if ($timestamp !== false): ?>
                            · <?= date('d/m H:i', $timestamp) ?>
                          <?php endif; ?>
                        </small>
                      </span>
                    </a>
                <?php endforeach;
                endif; ?>
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
          <div class="card-admin admin-table-scroll">
            <table class="data-table" id="prodTable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Nome do produto relacionado</th>
                  <th>Preço</th>
                  <th>Estoque</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="prodBody">
                <?php if (empty($produtos)): ?>
                  <tr>
                    <td colspan="5" style="text-align:center; color:var(--roxo); padding: 30px;">Nenhum produto cadastrado na base.</td>
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
                <?php endforeach;
                endif; ?>
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
          <div class="card-admin admin-table-scroll">
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
                    <td colspan="5" style="text-align:center; color:var(--roxo); padding: 30px;">Não há registros de pedidos.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                      <td>#<?= (int) $pedido['id'] ?></td>
                      <td><?= htmlspecialchars($pedido['cliente'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td>R$ <?= number_format(((int) $pedido['total']) / 100, 2, ',', '.') ?></td>
                      <td>
                        <?= htmlspecialchars($pedido['status'], ENT_QUOTES, 'UTF-8') ?>
                        <small class="d-block text-secondary">
                          Pagamento: <?= htmlspecialchars($pedido['statusPag'], ENT_QUOTES, 'UTF-8') ?>
                        </small>
                      </td>
                      <td>
                        <a class="action-btn" href="mod.php?tipo=pedido&id=<?= (int) $pedido['id'] ?>" title="Editar pedido">
                          <i data-feather="edit-2"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
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
          <div class="card-admin admin-table-scroll">
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
                    <td colspan="3" style="text-align:center; color:var(--roxo); padding: 30px;">Nenhuma conta de cliente
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
                <?php endforeach;
                endif; ?>
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
          <div class="card-admin admin-table-scroll">
            <table class="data-table" id="cursoTable">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Produto relacionado</th>
                  <th>Nível</th>
                  <th>Carga horária</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($cursos)): ?>
                  <tr>
                    <td colspan="5" style="text-align:center; color:var(--roxo); padding: 30px;">A biblioteca de tutoriais está vazia no momento.</td>
                  </tr>
                  <?php else: foreach ($cursos as $c): ?>
                    <tr>
                      <td><?= htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($c['produto_nome'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($c['nivel'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= (int) $c['cargaH'] ?> h</td>
                      <td>
                        <a class="action-btn" href="mod.php?tipo=curso&id=<?= (int) $c['id'] ?>" title="Editar"><i data-feather="edit-2"></i></a>
                      </td>
                    </tr>
                <?php endforeach;
                endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-wrap" id="toastWrap"></div>
  
  <?php include '../../components/footer.php'; ?>

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

    function logout() {
      window.location.href = '../../scriptsPHP/sair.php';
    }

    <?php if (isset($form_success) && $form_success): ?>
      showToast('check-circle', 'Produto registrado com sucesso.', 'ok');
    <?php endif; ?>
  </script>
</body>

</html>