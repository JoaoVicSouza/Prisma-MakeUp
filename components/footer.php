<?php $arquivo_atual = basename($_SERVER['PHP_SELF']);
    $pasta_atual = basename(dirname($_SERVER['PHP_SELF']));
    $isCRUD      = ($pasta_atual === 'CRUD');
    $prefix_up   = $isCRUD ? '../../' : '../';
    if($arquivo_atual == "index.php"){ $isIndex = true; }else{ $isIndex = false; }
?>
<footer class="border-top border-1 border-rosaChoque bg-rosa w-100 p-3">
        <div class="text-center">
            <h4 class="mb-1 <?= $isCRUD ? 'admin-footer-brand' : '' ?>">Prisma <span class="text-rosaChoque">MakeUp</span></h4>
            <ul class="list-unstyled d-flex justify-content-center gap-4 mb-1">
                    <li><a class="text-decoration-none text-secondary"  href="<?php if($isIndex){echo "#";}else{ echo $prefix_up . "index.php";}?>">Sobre</a></li>
                    <li><a class="text-decoration-none text-secondary"  href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>produtos.php">Produtos</a></li>
                    <li><a class="text-decoration-none text-secondary" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>cursos.php">Aprender</a></li>
                    <li><a class="text-decoration-none text-secondary" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>chat.php">Ariane</a></li>
            </ul>
            <p class="text-roxo m-0">© 2026 Prisma MakeUp. Todos os direitos reservados.</p>
        </div>
</footer>