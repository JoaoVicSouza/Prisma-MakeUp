<?php
require_once __DIR__ . '/../../scriptsPHP/conexao.php';

$stmt = $pdo->prepare(
    'SELECT c.id, c.nome, c.nivel, c.cargaH
     FROM cursos AS c
     INNER JOIN produtos AS p ON p.id = c.id_produto
     WHERE LOWER(TRIM(p.categoria)) IN (:categoria, :preparacao)
     ORDER BY p.nome,
              CASE LOWER(TRIM(c.nivel))
                  WHEN \'iniciante\' THEN 1
                  WHEN \'intermediário\' THEN 2
                  WHEN \'intermediario\' THEN 2
                  WHEN \'avançado\' THEN 3
                  WHEN \'avancado\' THEN 3
                  ELSE 4
              END,
              c.nome'
);
$stmt->execute([
    'categoria' => 'pele',
    'preparacao' => 'preparação de pele',
]);
$cursosLista = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="pele" class="secao">
    <div class="container-titulo-linhas d-flex align-items-center mb-5 my-5">
        <div class="linha-lateral flex-grow-1"></div>
        <div class="text-center px-4">
            <h2 class="titulo-principal m-0">Pele</h2>
            <span class="subtitulo">cursos exclusivos para aprimorar suas técnicas</span>
        </div>
        <div class="linha-lateral flex-grow-1"></div>
    </div>
    <?php include __DIR__ . '/cards.php'; ?>
</section>
