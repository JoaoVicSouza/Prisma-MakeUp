<section class="bg-azul py-5 mt-4 overflow-hidden">

    <div class="carousel-patrocinadores">
        <div class="carousel-track">
            <?php
            $stickers = [
                ['img' => 'assets/stickers/sticker1.svg', 'alt' => 'Sticker 1'],
                ['img' => 'assets/stickers/sticker2.svg', 'alt' => 'Sticker 2'],
                ['img' => 'assets/stickers/sticker3.svg', 'alt' => 'Sticker 3'],
                ['img' => 'assets/stickers/sticker4.svg', 'alt' => 'Sticker 4'],
            ];

            $Sponsors = '';
            foreach ($stickers as $s) {
                $Sponsors .= '
                <div class="sticker-item flex-shrink-0">
                    <img src="' . htmlspecialchars($s['img']) . '" alt="' . htmlspecialchars($s['alt']) . '" loading="lazy">
                </div>';
            }
            ?>

            <div class="carousel-group">
                <?php for ($i = 0; $i < 3; $i++) { echo $Sponsors; } ?>
            </div>

            <div class="carousel-group" aria-hidden="true">
                <?php for ($i = 0; $i < 3; $i++) { echo $Sponsors; } ?>
            </div>
        </div>
    </div>
</section>