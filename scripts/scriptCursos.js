document.addEventListener("DOMContentLoaded", function() {
    // 1. Seleciona todos os links da aba lateral
    const linksMenu = document.querySelectorAll('#sidebarCursos .nav-link');

    // 2. Adiciona um "ouvinte de cliques" para cada link
    linksMenu.forEach(link => {
        link.addEventListener('click', function() {
            
            // Passo A: Remove a classe 'active' de TODOS os links
            linksMenu.forEach(l => {
                l.classList.remove('active');
                l.classList.add('text-dark'); // Garante que os inativos fiquem com a cor de texto escura
            });

            // Passo B: Adiciona a classe 'active' APENAS no link que você acabou de clicar
            this.classList.add('active');
            this.classList.remove('text-dark'); // Remove o texto escuro para o botão roxo destacar a letra branca
        });
    });
});