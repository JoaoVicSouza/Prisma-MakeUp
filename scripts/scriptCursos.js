document.addEventListener("DOMContentLoaded", function() {
    const linksMenu = document.querySelectorAll('.prisma-nav-link');

    linksMenu.forEach(link => {
        link.addEventListener('click', function(e) {
            
            // 1. Remove a classe active de todos
            linksMenu.forEach(l => l.classList.remove('active'));
            
            // 2. Adiciona apenas no link clicado
            this.classList.add('active');

            // --- CORREÇÃO DO SCROLL (DESLIZAMENTO) ---
            
            // Pega o ID para onde o link quer ir (ex: "#pele" ou "#olhos")
            const idAlvo = this.getAttribute('href');
            
            // Ignora se for o "#todos" que volta pro topo
            if (idAlvo === "#" || idAlvo === "#todos") {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            // Procura a seção exata no seu HTML
            const secao = document.querySelector(idAlvo);
            
            if (secao) {
                // O setTimeout dá 200 milissegundos para o menu do celular fechar antes de começar a rolar a tela, deixando o efeito perfeito!
                setTimeout(() => {
                    secao.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 200);
            }
        });
    });
});