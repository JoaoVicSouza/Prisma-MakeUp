<section class="bg-azul py-5 mt-4 overflow-hidden">
            <h3 class="fs-4 fw-bold text-center text-secondary mb-5">Apoiadores e Marcas Parceiras</h3>

            <div class="carousel-patrocinadores">
                <div class="carousel-track">
                    <?php 
                    
                        $Sponsors = '<div class="d-flex align-items-center gap-2 opacity-75 flex-shrink-0">
                            <i class="bi bi-stars fs-2 text-rosaChoque"></i>
                            <h4 class="m-0 fw-bold text-muted">DermoCare</h4>
                        </div>
                        <div class="d-flex align-items-center gap-2 opacity-75 flex-shrink-0">
                            <i class="bi bi-flower1 fs-2 text-roxo"></i>
                            <h4 class="m-0 fw-bold text-muted">NatureLabs</h4>
                        </div>
                        <div class="d-flex align-items-center gap-2 opacity-75 flex-shrink-0">
                            <i class="bi bi-droplet-half fs-2 text-rosaChoque"></i>
                            <h4 class="m-0 fw-bold text-muted">PureSkin</h4>
                        </div>
                        <div class="d-flex align-items-center gap-2 opacity-75 flex-shrink-0">
                            <i class="bi bi-gem fs-2 text-roxo"></i>
                            <h4 class="m-0 fw-bold text-muted">GlowUp</h4>
                        </div>';
                    
                    ?>
                    <div class="carousel-group">
                        <?php 
                            for ($i = 0; $i < 3; $i++) {
                                echo $Sponsors;
                            }?>
                    </div>

                    <div class="carousel-group" aria-hidden="true">
                       <?php 
                            for ($i = 0; $i < 3; $i++) {
                                echo $Sponsors;
                            }?>
                    </div>

                </div>
            </div>
        </section>