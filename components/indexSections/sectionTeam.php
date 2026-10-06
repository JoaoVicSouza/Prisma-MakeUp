<section class="cards bg-purple w-100 py-5">
    <!-- Adicionado 'position-relative' para o posicionamento absoluto dos botões laterais -->
    <div class="px-5 d-flex flex-column align-items-center justify-content-center position-relative">
        <h2 class="section-title">Nossa Equipe</h2>

        <!-- Botão Lateral Esquerdo (Oculto no mobile via d-none d-md-block) -->
        <button class="nav-btn bg-green2 position-absolute start-0 top-50 translate-middle-y d-none d-md-block border-0 rounded-circle fs-3" 
                onclick="voltar()" 
                style="margin-left: 20px; z-index: 10; width: 50px; height: 50px;">
            ‹
        </button>

        <div class="scene">
            <div class="stage" id="stage">
                <div class="card bg-green border-green text-center p-4 d-flex flex-column align-items-center justify-content-center" data-i="0">
                    <img class="img-fluid rounded-circle border border-2 border-purple2 mb-3"
                        src="assets/helderIcon.jpg" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-2 mb-1 fw-bold fs-4">Helder</h5>
                    <p class="text-purple2 mb-2 fw-bold" style="font-size: 16px;">CEO & Diretor Criativo</p>
                    <p class="m-0 text-secondary" style="font-size: 15px; line-height: 1.4;">Guia a visão
                        artística e vibrante da Prisma.</p>
                </div>

                <div class="card bg-green border-green text-center p-4 d-flex flex-column align-items-center justify-content-center" data-i="1">
                    <img class="img-fluid rounded-circle border border-2 border-purple2 mb-3"
                        src="assets/liviaIcon.jpg" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-2 mb-1 fw-bold fs-4">Lívia</h5>
                    <p class="text-purple2 mb-2 fw-bold" style="font-size: 16px;">Diretora de Inclusão & Mkt</p>
                    <p class="m-0 text-secondary" style="font-size: 15px; line-height: 1.4;">Conecta a marca às
                        reais necessidades do público.</p>
                </div>

                <div class="card bg-green border-green text-center p-4 d-flex flex-column align-items-center justify-content-center" data-i="2">
                    <img class="img-fluid rounded-circle border border-2 border-purple2 mb-3"
                        src="assets/enzoIcon.jpg" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-2 mb-1 fw-bold fs-4">Enzo</h5>
                    <p class="text-purple2 mb-2 fw-bold" style="font-size: 16px;">Web Designer & Front-end</p>
                    <p class="m-0 text-secondary" style="font-size: 15px; line-height: 1.4;">Cria a experiência
                        visual e a interface da plataforma.</p>
                </div>

                <div class="card bg-green border-green text-center p-4 d-flex flex-column align-items-center justify-content-center" data-i="3">
                    <img class="img-fluid rounded-circle border border-2 border-purple2 mb-3"
                        src="assets/joaoVIcon.jpg" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-2 mb-1 fw-bold fs-4">João Victor</h5>
                    <p class="text-purple2 mb-2 fw-bold" style="font-size: 16px;">CTO & Dev FullStack</p>
                    <p class="m-0 text-secondary" style="font-size: 15px; line-height: 1.4;">Cuida da estrutura
                        tecnológica e inovação do site.</p>
                </div>

                <div class="card bg-green border-green text-center p-4 d-flex flex-column align-items-center justify-content-center" data-i="4">
                    <img class="img-fluid rounded-circle border border-2 border-purple2 mb-3"
                        src="assets/joaoHIcon.jpg" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-2 mb-1 fw-bold fs-4">João Henrique</h5>
                    <p class="text-purple2 mb-2 fw-bold" style="font-size: 16px;">Diretor de Estratégia</p>
                    <p class="m-0 text-secondary" style="font-size: 15px; line-height: 1.4;">Focado na expansão
                        comercial e em novas parcerias.</p>
                </div>
            </div>

            <div class="bottom-ui">
                <div class="card-title-display" id="title-display">Helder</div>
                <div class="nav-row">
                    <button class="nav-btn bg-green2" onclick="voltar()">‹</button>
                    <div class="dots-row" id="dots">
                        <div class="dot2 on"></div>
                        <div class="dot2"></div>
                        <div class="dot2"></div>
                        <div class="dot2"></div>
                        <div class="dot2"></div>
                    </div>
                    <button class="nav-btn bg-green2" onclick="avancar()">›</button>
                </div>
            </div>
        </div>

        <!-- Botão Lateral Direito (Oculto no mobile via d-none d-md-block) -->
        <button class="nav-btn bg-green2 position-absolute end-0 top-50 translate-middle-y d-none d-md-block border-0 rounded-circle fs-3" 
                onclick="avancar()" 
                style="margin-right: 20px; z-index: 10; width: 50px; height: 50px;">
            ›
        </button>

    </div>
</section>