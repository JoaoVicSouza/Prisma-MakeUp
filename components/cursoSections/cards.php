<?php if ($cursosLista): ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mt-4 my-5">
        <?php foreach ($cursosLista as $curso): ?>
            <?php
            $nivel = trim((string) ($curso['nivel'] ?? 'Todos os níveis'));
            $classeNivel = match (strtolower($nivel)) {
                'iniciante', 'todos os níveis', 'todos os niveis' => 'curso-dificuldade--facil',
                'avançado', 'avancado' => 'curso-dificuldade--dificil',
                default => 'curso-dificuldade--medio',
            };
            ?>
            <div class="col">
                <article class="curso-card h-100">
                    <div class="curso-card-conteudo">
                        <h3 class="curso-card-titulo">
                            <?= htmlspecialchars($curso['nome'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>
                        <div class="curso-card-detalhes">
                            <span class="curso-card-carga"><?= (int) $curso['cargaH'] ?> horas</span>
                            <span class="curso-dificuldade <?= $classeNivel ?>">
                                <?= htmlspecialchars($nivel, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                        <a
                            class="btn btn-outline-dark btn-sm mt-auto"
                            href="https://www.patreon.com/cw/PrismaMakeUp"
                            target="_blank"
                            rel="noopener noreferrer">
                            <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>
                            Ver no Patreon
                        </a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-center text-muted py-4">Ainda não há cursos disponíveis para esta região.</p>
<?php endif; ?>
