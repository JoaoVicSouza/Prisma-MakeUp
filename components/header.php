<?php 
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
$arquivo_atual = basename($_SERVER['PHP_SELF']);
$pasta_atual   = basename(dirname($_SERVER['PHP_SELF']));
$isCRUD        = ($pasta_atual === 'CRUD');
$prefix_up     = $isCRUD ? '../../' : '../';
if($arquivo_atual == "index.php"){ $isIndex = true; }else{ $isIndex = false; }
if($arquivo_atual !== "admin.php" && $arquivo_atual !== "produtos.php" && $arquivo_atual !== "cursos.php" && $arquivo_atual !== "mod.php"){
    $headerFix = true;
}else{ $headerFix = false; }
?>
<header id="main-header" class="<?php if($headerNotFix){ echo "fixed-top w-100 z-3";}?>"
        style="transition: background-color 0.4s ease, backdrop-filter 0.4s ease;">
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
                            <a class="nav-link p-0 <?php if($isIndex){echo "text-green2";}?>" href="<?php if($isIndex){echo "#";}else{ echo $prefix_up . "index.php";}?>">SOBRE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($arquivo_atual == "produtos.php"){echo "text-green2";}?>" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>produtos.php">PRODUTOS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($arquivo_atual == "curso.php"){echo "text-green2";}?>" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>cursos.php">APRENDER</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-0 <?php if($arquivo_atual == "chat.php"){echo "text-green2";}?>" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>chat.php">ARIANE</a>
                        </li>
                        <li class="nav-item fw-bold rounded-pill bg-purple2 p-2 px-3">
                            <a class="nav-link p-0 text-white" href="<?php if($isIndex){echo "./pages/";}else{ echo $prefix_up . "pages/";}?>login.php">
                                <i class="bi bi-person me-1"></i>
                                <?php echo isset($_SESSION['usuario_nome']) ? 'Olá, ' . htmlspecialchars(explode(' ', $_SESSION['usuario_nome'])[0]) : 'Login'; ?>
                            </a>
                        </li>
                        
                    </ul>
                </div>

            </div>
        </nav>
    </header>
