<?php 
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
$arquivo_atual = basename($_SERVER['PHP_SELF']);
$pasta_atual   = basename(dirname($_SERVER['PHP_SELF']));
$isCRUD        = ($pasta_atual === 'CRUD');
$prefix_up     = $isCRUD ? '../../' : '../';
if($arquivo_atual == "index.php"){ $isIndex = true; }else{ $isIndex = false; }
?>

<header id="main-header" class="fixed-top w-100 z-3 bg-rosa-transparent border-bottom border-1 border-rosaChoque">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid ms-0">

                <a href="<?php if(!$isIndex){ echo $prefix_up; } ?>index.php" class="navbar-brand d-inline-block p-0 m-2">
                    <img src="<?php if(!$isIndex){ echo $prefix_up; } ?>assets/logo.png" alt="logo" class="img-fluid" style="max-width: 250px;">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#MenuNav"
                    aria-controls="MenuNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse text-center mt-3 mt-lg-0" id="MenuNav">
                    <ul class="navbar-nav ms-auto d-flex flex-column flex-lg-row align-items-center gap-3 gap-lg-4">
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($isIndex){echo "text-rosaChoque";}?>" href="<?php if($isIndex){echo "#";}else{ echo $prefix_up . "index.php";}?>">SOBRE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($arquivo_atual == "produtos.php"){echo "text-rosaChoque";}?>" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>produtos.php">PRODUTOS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($arquivo_atual == "cursos.php"){echo "text-rosaChoque";}?>" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>cursos.php">APRENDER</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($arquivo_atual == "chat.php"){echo "text-rosaChoque";}?>" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>chat.php">ARIANE</a>
                        </li>
                        <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
                            <li class="nav-item">
                                <a class="nav-link p-0 <?php if($arquivo_atual == "admin.php"){echo "text-rosaChoque";}?>" href="<?php if($isIndex){echo "./pages/CRUD/";}else{ echo $prefix_up . "pages/CRUD/";}?>admin.php">ADMIN</a>
                            </li>
                        <?php endif ?>
                        <li class="nav-item fw-bold rounded-pill bg-rosaChoque p-2 px-3">
                        <?php if(isset($_SESSION['usuario_nome'])): ?>
                            <a class="nav-link p-0 text-white" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>perfil.php">
                                <i class="bi bi-person me-1"></i>
                                Olá, <?= htmlspecialchars(explode(' ', $_SESSION['usuario_nome'])[0]) ?>
                            </a>
                        <?php else: ?>
                            <a class="nav-link p-0 text-white" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>login.php">
                                <i class="bi bi-person me-1"></i>
                                Login
                            </a>
                        <?php endif ?>
                        </li>
                        
                    </ul>
                </div>

            </div>
        </nav>
    </header>
