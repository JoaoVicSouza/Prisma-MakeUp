const header = document.getElementById('main-header');
const caminhoDaPagina = window.location.pathname;
const nomeDoArquivo = caminhoDaPagina.substring(caminhoDaPagina.lastIndexOf('/') + 1);
const arquivosNotHeader = ["login.php", "chat.php", "admin.php", "compra.php", "mod.php", "cadCurso.php", "cadProd.php", "produto.php", "pedidos.php"];
        let isScrolling = false;

        window.addEventListener('scroll', function () {
            if (!isScrolling) {
                window.requestAnimationFrame(function () {
                    if (window.scrollY >= window.innerHeight - 70 || arquivosNotHeader.includes(nomeDoArquivo)) {
                        header.classList.add('bg-rosa-transparent', 'border-bottom', 'border-1', 'border-rosaChoque');
                    } else {
                        header.classList.remove('bg-rosa-transparent', 'border-bottom', 'border-1', 'border-rosaChoque');
                    }
                    isScrolling = false;
                });
                isScrolling = true;
            }
        }, { passive: true });

        window.dispatchEvent(new Event('scroll'));

document.addEventListener('DOMContentLoaded', function () {
    if (arquivosNotHeader.includes(nomeDoArquivo) || window.scrollY >= window.innerHeight - 70) {
        header.classList.add('bg-rosa-transparent', 'border-bottom', 'border-1', 'border-rosaChoque');
    }
});