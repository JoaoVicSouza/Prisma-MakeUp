<section class="bg-roxo py-5 mt-4 overflow-hidden">

    <div class="carousel-patrocinadores">
        <div class="carousel-track">
            <?php
            $stickers = [
                ['img' => 'assets/stickers/sticker1.svg', 'alt' => 'Sticker 1'],
                ['img' => 'assets/stickers/sticker2.svg', 'alt' => 'Sticker 2'],
                ['img' => 'assets/stickers/sticker3.svg', 'alt' => 'Sticker 3'],
                ['img' => 'assets/stickers/sticker4.svg', 'alt' => 'Sticker 4'],
                ['img' => 'assets/stickers/sticker5.svg', 'alt' => 'Sticker 5'],
                ['img' => 'assets/stickers/sticker6.svg', 'alt' => 'Sticker 6'],
                ['img' => 'assets/stickers/sticker7.svg', 'alt' => 'Sticker 7'],
                ['img' => 'assets/stickers/sticker8.svg', 'alt' => 'Sticker 8'],
                ['img' => 'assets/stickers/sticker9.svg', 'alt' => 'Sticker 9'],
                ['img' => 'assets/stickers/sticker10.svg', 'alt' => 'Sticker 10'],
                ['img' => 'assets/stickers/sticker11.svg', 'alt' => 'Sticker 11'],
                ['img' => 'assets/stickers/sticker12.svg', 'alt' => 'Sticker 12'],
                ['img' => 'assets/stickers/sticker13.svg', 'alt' => 'Sticker 13'],
                ['img' => 'assets/stickers/sticker14.svg', 'alt' => 'Sticker 14'],
                ['img' => 'assets/stickers/sticker15.svg', 'alt' => 'Sticker 15'],
                ['img' => 'assets/stickers/sticker16.svg', 'alt' => 'Sticker 16'],
                ['img' => 'assets/stickers/sticker17.svg', 'alt' => 'Sticker 17'],
                ['img' => 'assets/stickers/sticker18.svg', 'alt' => 'Sticker 18'],
                ['img' => 'assets/stickers/sticker19.svg', 'alt' => 'Sticker 19'],
                ['img' => 'assets/stickers/sticker20.svg', 'alt' => 'Sticker 20'],
                ['img' => 'assets/stickers/sticker21.svg', 'alt' => 'Sticker 21'],
                ['img' => 'assets/stickers/sticker22.svg', 'alt' => 'Sticker 22'],
                ['img' => 'assets/stickers/sticker23.svg', 'alt' => 'Sticker 23'],
                ['img' => 'assets/stickers/sticker24.svg', 'alt' => 'Sticker 24'],
                ['img' => 'assets/stickers/sticker25.svg', 'alt' => 'Sticker 25'],
                ['img' => 'assets/stickers/sticker26.svg', 'alt' => 'Sticker 26'],
                ['img' => 'assets/stickers/sticker27.svg', 'alt' => 'Sticker 27'],
                ['img' => 'assets/stickers/sticker28.svg', 'alt' => 'Sticker 28'],
                ['img' => 'assets/stickers/sticker29.svg', 'alt' => 'Sticker 29'],
                ['img' => 'assets/stickers/sticker30.svg', 'alt' => 'Sticker 30'],
                ['img' => 'assets/stickers/sticker31.svg', 'alt' => 'Sticker 31'],
                ['img' => 'assets/stickers/sticker32.svg', 'alt' => 'Sticker 32'],
                ['img' => 'assets/stickers/sticker33.svg', 'alt' => 'Sticker 33'],
                ['img' => 'assets/stickers/sticker34.svg', 'alt' => 'Sticker 34'],
                ['img' => 'assets/stickers/sticker35.svg', 'alt' => 'Sticker 35'],
                ['img' => 'assets/stickers/sticker36.svg', 'alt' => 'Sticker 36'],
                ['img' => 'assets/stickers/sticker37.svg', 'alt' => 'Sticker 37'],
                ['img' => 'assets/stickers/sticker38.svg', 'alt' => 'Sticker 38'],
                ['img' => 'assets/stickers/sticker39.svg', 'alt' => 'Sticker 39'],
                ['img' => 'assets/stickers/sticker40.svg', 'alt' => 'Sticker 40'],
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