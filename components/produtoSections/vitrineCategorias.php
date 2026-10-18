<?php
require_once __DIR__ . '/../../scriptsPHP/conexao.php';

$categorias = [
    'pele' => [
        'titulo'    => 'Pele',
        'subtitulo' => 'adeus olheiras, olá pele perfeita',
        'imagem'    => '../assets/meninas/naju4.jpg',
        'banco'     => ['Pele', 'Preparação de pele'],
    ],
    'olhos' => [
        'titulo'    => 'Olhos',
        'subtitulo' => 'cor intensa e brilho espelhado em um só duo',
        'imagem'    => '../assets/meninas/kayke1.jpg',
        'banco'     => ['Olhos'],
    ],
    'sobrancelhas' => [
        'titulo'    => 'Sobrancelhas',
        'subtitulo' => 'definição e preenchimento para realçar o olhar',
        'imagem'    => '../assets/maquiagens/blush2.jpg',
        'banco'     => ['Sobrancelhas'],
    ],
    'labios' => [
        'titulo'    => 'Lábios',
        'subtitulo' => 'toque aveludado e acabamento impecável',
        'imagem'    => '../assets/meninas/naju3.jpg',
        'banco'     => ['Lábios'],
    ],
    'kits' => [
        'titulo'    => 'Kits',
        'subtitulo' => 'combinações práticas para completar sua maquiagem',
        'imagem'    => '../assets/maquiagens/kit.jpg',
        'banco'     => ['kit', 'acessórios'],
    ],
];

/* Mapa "nome no banco (minúsculo)" => slug, para distribuir os produtos nas abas */
$mapaBanco = [];
$valoresBanco = [];
foreach ($categorias as $slug => $cat) {
    foreach ($cat['banco'] as $nomeBanco) {
        $mapaBanco[mb_strtolower($nomeBanco, 'UTF-8')] = $slug;
        $valoresBanco[] = $nomeBanco;
    }
}

/* Uma única consulta traz todos os produtos de todas as categorias */
$placeholders = implode(', ', array_fill(0, count($valoresBanco), '?'));
$stmt = $pdo->prepare(
    "SELECT id, nome, descricao, preco, imagem, categoria
     FROM produtos
     WHERE categoria IN ($placeholders)
       AND estoque > 0
     ORDER BY nome"
);
$stmt->execute($valoresBanco);

$produtosPorCategoria = array_fill_keys(array_keys($categorias), []);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $produto) {
    $slug = $mapaBanco[mb_strtolower($produto['categoria'], 'UTF-8')] ?? null;
    if ($slug !== null) {
        $produtosPorCategoria[$slug][] = $produto;
    }
}

$categoriaInicial = 'sobrancelhas';

if (!function_exists('e')) {
    function e($texto): string
    {
        return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
    }
}
?>

<style>
    /* ===== Sub navbar de categorias ===== */
    #vitrineProdutos {
        --vt-rosa: var(--roxo);
        --vt-rosa-claro: var(--rosa);
        --vt-magenta: var(--roxo);
        --vt-azul: var(--azul);
        --vt-topo: 95px;
    }

    .vt-subnav {
        position: sticky;
        top: var(--vt-topo);
        z-index: 100;
        background: rgba(255, 255, 255, .88);
        -webkit-backdrop-filter: blur(8px);
        backdrop-filter: blur(8px);
    }

    .vt-subnav-scroll {
        position: relative;
        display: flex;
        gap: clamp(18px, 5vw, 56px);
        overflow-x: auto;
        padding: 16px 20px 12px;
        scroll-snap-type: x proximity;
        scrollbar-width: none;
    }

    .vt-subnav-scroll::-webkit-scrollbar {
        display: none;
    }

    /* centraliza quando cabe e deixa rolar quando não cabe (sem cortar o primeiro item) */
    .vt-cat:first-child {
        margin-left: auto;
    }

    .vt-cat:last-child {
        margin-right: auto;
    }

    .vt-cat {
        flex: 0 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 0;
        border: 0;
        background: none;
        cursor: pointer;
        scroll-snap-align: center;
        font: inherit;
        color: var(--roxo);
    }

    .vt-cat-circulo {
        display: block;
        width: clamp(72px, 18vw, 112px);
        aspect-ratio: 1;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid transparent;
        outline: 3px solid transparent;
        outline-offset: 0;
        opacity: .72;
        transition: opacity .2s, border-color .2s, outline-color .2s;
    }

    .vt-cat-circulo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .vt-cat:hover .vt-cat-circulo {
        opacity: 1;
    }

    .vt-cat.ativo .vt-cat-circulo {
        opacity: 1;
        border-color: var(--bege);
        outline-color: var(--vt-rosa);
    }

    .vt-cat-nome {
        font-size: .95rem;
        padding-bottom: 4px;
        border-bottom: 2px solid transparent;
        transition: color .2s, border-color .2s;
    }

    .vt-cat.ativo .vt-cat-nome {
        color: var(--vt-magenta);
        font-weight: 600;
        border-bottom-color: var(--vt-rosa);
    }

    .vt-cat:focus-visible {
        outline: none;
    }

    .vt-cat:focus-visible .vt-cat-circulo {
        outline-color: var(--vt-azul);
    }

    /* ===== Painel da categoria ===== */
    .vt-container {
        width: min(92%, 1200px);
        margin: 0 auto;
    }

    .vt-painel:not([hidden]) {
        animation: vtEntrada .3s ease;
    }

    @keyframes vtEntrada {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* reaproveita .linha-lateral / .titulo-principal / .subtitulo do styleProdutos */
    .vt-titulo {
        width: 100%;
        margin: 2.2rem auto 1.8rem;
    }

    /* ===== Cards ===== */
    .vt-card {
        border-radius: 13px;
        overflow: hidden;
        height: 400px;
        display: flex;
        flex-direction: column;
    }

    .vt-card-img {
        flex: 0 0 180px;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .vt-card .card-body {
        flex: 1;
        min-height: 0;
    }

    .vt-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vt-card .card-title {
        font-size: 1.1rem;
        height: 2.4em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .vt-desc {
        font-size: .9rem;
        display: -webkit-box;
        line-height: 1.3;
        height: 3.9em;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .vt-card .preco-produto {
        font-size: 1.15rem;
    }

    .vt-card .btn-comprar {
        font-size: 14px;
    }

    .vt-vazio {
        text-align: center;
        padding: 4rem 0;
        color: #6c757d;
    }

    @media (max-width: 767.98px) {
        .vt-card .card-title { font-size: .95rem; }
        .vt-desc { font-size: .8rem; height: 2.6em; -webkit-line-clamp: 2; }
        .vt-card .preco-produto { font-size: 1rem; }
        .vt-card .btn-comprar { font-size: 12px; padding: .4rem; }
        .vt-cat-nome { font-size: .85rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .vt-painel:not([hidden]) { animation: none; }
        .vt-cat-circulo, .vt-cat-nome { transition: none; }
    }
</style>

<main id="vitrineProdutos" class="pb-5">

    <!-- SUB NAVBAR: categorias em bolinhas -->
    <nav class="vt-subnav" aria-label="Categorias de produtos">
        <div class="vt-subnav-scroll" role="tablist">
            <?php foreach ($categorias as $slug => $cat): ?>
                <?php $ativo = ($slug === $categoriaInicial); ?>
                <button type="button"
                        class="vt-cat<?= $ativo ? ' ativo' : '' ?>"
                        role="tab"
                        id="aba-<?= e($slug) ?>"
                        aria-controls="painel-<?= e($slug) ?>"
                        aria-selected="<?= $ativo ? 'true' : 'false' ?>"
                        tabindex="<?= $ativo ? '0' : '-1' ?>"
                        data-categoria="<?= e($slug) ?>">
                    <span class="vt-cat-circulo">
                        <img src="<?= e($cat['imagem']) ?>" alt="" loading="lazy">
                    </span>
                    <span class="vt-cat-nome"><?= e($cat['titulo']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- PAINÉIS: um por categoria, só o ativo aparece -->
    <?php foreach ($categorias as $slug => $cat): ?>
        <?php $produtos = $produtosPorCategoria[$slug]; ?>
        <section class="vt-painel"
                 role="tabpanel"
                 id="painel-<?= e($slug) ?>"
                 aria-labelledby="aba-<?= e($slug) ?>"
                 <?= $slug === $categoriaInicial ? '' : 'hidden' ?>>

            <div class="vt-container">
                <div class="container-titulo-linhas vt-titulo d-flex align-items-center">
                    <div class="linha-lateral flex-grow-1"></div>
                    <div class="text-center px-4">
                        <h2 class="titulo-principal m-0"><?= e($cat['titulo']) ?></h2>
                        <span class="subtitulo"><?= e($cat['subtitulo']) ?></span>
                    </div>
                    <div class="linha-lateral flex-grow-1"></div>
                </div>

                <?php if ($produtos): ?>
                    <div class="row row-cols-2 row-cols-lg-4 g-3 g-md-4 justify-content-center">
                        <?php foreach ($produtos as $produto): ?>
                            <div class="col">
                                <article class="card vt-card h-100 border-0 shadow-sm">
                                    <div class="vt-card-img">
                                        <?php if (!empty($produto['imagem'])): ?>
                                            <img src="../<?= e($produto['imagem']) ?>" alt="<?= e($produto['nome']) ?>" loading="lazy">
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body d-flex flex-column p-3">
                                        <h6 class="card-title mb-1 fw-bold"><?= e($produto['nome']) ?></h6>
                                        <p class="card-text text-muted vt-desc"><?= e($produto['descricao'] ?? '') ?></p>

                                        <div class="mt-auto">
                                            <div class="fw-bold mb-2 preco-produto text-roxo">R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?></div>
                                            <a class="btn btn-sm w-100 fw-bold rounded-pill text-white btn-comprar bg-rosaChoque"
                                               href="produto.php?id=<?= urlencode((string) $produto['id']) ?>">COMPRAR</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="vt-vazio">Nenhum produto disponível em <?= e(mb_strtolower($cat['titulo'], 'UTF-8')) ?> no momento.</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>

<script>
    (function () {
        const raiz = document.getElementById('vitrineProdutos');
        if (!raiz) return;

        const barra   = raiz.querySelector('.vt-subnav-scroll');
        const abas    = Array.from(raiz.querySelectorAll('.vt-cat'));
        const paineis = Array.from(raiz.querySelectorAll('.vt-painel'));

        function selecionar(slug, opcoes) {
            const o = Object.assign({ hash: true, suave: true }, opcoes);
            const aba = abas.find(a => a.dataset.categoria === slug);
            if (!aba) return;

            abas.forEach(a => {
                const ativa = a === aba;
                a.classList.toggle('ativo', ativa);
                a.setAttribute('aria-selected', ativa ? 'true' : 'false');
                a.tabIndex = ativa ? 0 : -1;
            });

            paineis.forEach(p => { p.hidden = (p.id !== 'painel-' + slug); });

            // centraliza a bolinha escolhida na barra (rolagem só horizontal, a página não pula)
            barra.scrollTo({
                left: aba.offsetLeft - (barra.clientWidth - aba.clientWidth) / 2,
                behavior: o.suave ? 'smooth' : 'auto'
            });

            if (o.hash) history.replaceState(null, '', '#' + slug);
        }

        abas.forEach((aba, i) => {
            aba.addEventListener('click', () => selecionar(aba.dataset.categoria));

            // setas esquerda/direita trocam de categoria
            aba.addEventListener('keydown', (ev) => {
                if (ev.key !== 'ArrowRight' && ev.key !== 'ArrowLeft') return;
                const passo = ev.key === 'ArrowRight' ? 1 : -1;
                const prox = abas[(i + passo + abas.length) % abas.length];
                selecionar(prox.dataset.categoria);
                prox.focus({ preventScroll: true });
            });
        });

        // abre direto na categoria do link (ex.: produtos.php#olhos)
        const hash = location.hash.slice(1);
        const abaAtiva = abas.find(a => a.classList.contains('ativo'));
        const categoriaInicial = abas.some(a => a.dataset.categoria === hash)
            ? hash
            : abaAtiva?.dataset.categoria;
        if (categoriaInicial) {
            selecionar(categoriaInicial, { hash: false, suave: false });
        }
    })();
</script>
