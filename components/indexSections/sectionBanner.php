<style>
  .prisma-banner {
    font-family: "Figtree", system-ui, sans-serif;
    background-image: url("assets/backgrounds/3.png");
    background-size: cover;
    overflow: hidden;
    width: 100%;
    height: 100vh;
    height: 100svh;
  }

  /* CELULAR (padrão): duas fotos lado a lado, cartão de texto sobreposto embaixo */
  .prisma-banner__inner {
    display: grid;
    width: 100%;
    height: 100%;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    grid-template-rows: minmax(0, 1fr) auto;
    grid-template-areas:
      "esq dir"
      "texto texto";
    margin: 0 auto;
  }

  .prisma-banner__foto {
    margin: 0;
    min-width: 0;
    min-height: 0;
    background: linear-gradient(160deg, var(--roxo), var(--azul));
  }
  .prisma-banner__foto img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 20%;
  }
  .prisma-banner__foto--esq { grid-area: esq; clip-path: polygon(0 0, 100% 0, 92% 100%, 0 100%); }
  .prisma-banner__foto--dir { grid-area: dir; clip-path: polygon(8% 0, 100% 0, 100% 100%, 0 100%); }

  .prisma-banner__texto {
    grid-area: texto;
    position: relative;
    z-index: 1;
    width: min(100% - 32px, 560px);
    margin: -56px auto 24px;
    padding: 24px 20px 28px;
    background: var(--bege);
    border-radius: 22px;
    box-shadow: 0 10px 30px color-mix(in srgb, var(--roxo) 18%, transparent);
    text-align: center;
  }
  .prisma-banner__titulo {
    position: relative;
    top: -20px;
    width: 100%;
    margin: 0;
  }
  .prisma-banner__titulo img {
    display: block;
    width: 100%;
    height: auto;
    margin: 0 auto;
  }
  .prisma-banner__descricao {
    max-width: 46ch;
    margin: 0 auto 20px;
    font-size: clamp(.95rem, 2.6vw, 1.05rem);
    line-height: 1.55;
    color: var(--rosaChoque);
  }
  /* TABLET */
  @media (min-width: 640px) {
    .prisma-banner__texto { margin-top: -72px; padding: 32px 36px 36px; }
  }

  /* DESKTOP: foto | texto | foto */
  @media (min-width: 1024px) {
    .prisma-banner__inner {
      grid-template-columns: minmax(0, 1fr) minmax(0, 1.3fr) minmax(0, 1fr);
      grid-template-rows: minmax(0, 1fr);
      grid-template-areas: "esq texto dir";
      align-items: stretch;
    }
    .prisma-banner__foto {
      height: 100%;
    }
    .prisma-banner__foto--esq { clip-path: polygon(0 0, 100% 0, 86% 100%, 0 100%); }
    .prisma-banner__foto--dir { clip-path: polygon(14% 0, 100% 0, 100% 100%, 0 100%); }
    .prisma-banner__texto {
      align-self: center;
      width: auto;
      margin: 0;
      padding: 24px 16px;
      background: var(--bege);
      box-shadow: 0 10px 30px color-mix(in srgb, var(--roxo) 18%, transparent);
      border-radius: 22px;
    }
    .prisma-banner__titulo {
      margin-bottom: 48px;
    }
  }
</style>

<section class="prisma-banner" aria-labelledby="prisma-banner-titulo">
  <div class="prisma-banner__inner">

    <figure class="prisma-banner__foto prisma-banner__foto--esq">
      <img src="assets/meninas/kayke3.png" alt="Modelo usando maquiagem Prisma" decoding="async">
    </figure>

    <div class="prisma-banner__texto">
      <h1 class="prisma-banner__titulo" id="prisma-banner-titulo">
        <img src="assets/logo.png" alt="Prisma MakeUp">
      </h1>
      <p class="prisma-banner__descricao">
        Cada rosto reflete a luz de um jeito. A Prisma Makeup nasceu para realçar a sua:
        maquiagem para pele, olhos, sobrancelhas e lábios, do básico ao look completo.
      </p>
    </div>

    <figure class="prisma-banner__foto prisma-banner__foto--dir">
      <img src="assets/meninas/gabi1.png" alt="Modelo com look completo Prisma" decoding="async">
    </figure>

  </div>
</section>