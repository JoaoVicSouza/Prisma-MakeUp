document.addEventListener("DOMContentLoaded", function() {
    const formFiltros = document.querySelector("#filtrosCursos");
    const busca = document.querySelector("#buscaCursos");
    const categoria = document.querySelector("#categoriaCursos");
    const nivel = document.querySelector("#nivelCursos");
    const resultado = document.querySelector("#resultadoCursos");
    const semResultado = document.querySelector("#semResultadoCursos");
    const secoes = Array.from(document.querySelectorAll("#listaCursos .secao"));
    const wave = document.querySelector(".cursos-wave");
    const conteudoCursos = document.querySelector(".cursos-page");
    let frameAgendado = false;

    function normalizar(texto) {
        return texto
            .toLocaleLowerCase("pt-BR")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .trim();
    }

    function filtrarCursos() {
        const termoBusca = normalizar(busca.value);
        const categoriaSelecionada = categoria.value;
        const nivelSelecionado = nivel.value;
        let totalEncontrado = 0;

        secoes.forEach(secao => {
            const categoriaCompativel =
                categoriaSelecionada === "todos" ||
                secao.dataset.categoria === categoriaSelecionada;
            const cursos = Array.from(secao.querySelectorAll(".curso-item"));

            cursos.forEach(item => {
                const nivelCurso = normalizar(
                    item.querySelector(".curso-dificuldade").dataset.nivel
                );
                const textoCurso = normalizar(item.textContent);
                const nivelCompativel =
                    nivelSelecionado === "todos" ||
                    nivelCurso === nivelSelecionado;
                const buscaCompativel =
                    termoBusca === "" || textoCurso.includes(termoBusca);
                const visivel =
                    categoriaCompativel && nivelCompativel && buscaCompativel;

                item.hidden = !visivel;
                if (visivel) totalEncontrado++;
            });

            const secaoSemCursos = cursos.length === 0;
            const exibirSecaoVazia =
                secaoSemCursos &&
                categoriaCompativel &&
                termoBusca === "" &&
                nivelSelecionado === "todos";

            secao.hidden =
                !categoriaCompativel ||
                (secaoSemCursos ? !exibirSecaoVazia : !cursos.some(item => !item.hidden));
        });

        resultado.textContent =
            `${totalEncontrado} ${totalEncontrado === 1 ? "curso encontrado" : "cursos encontrados"}`;
        semResultado.hidden = totalEncontrado > 0;
        agendarPosicionamentoOnda();
    }

    formFiltros.addEventListener("submit", event => event.preventDefault());
    formFiltros.addEventListener("input", filtrarCursos);
    formFiltros.addEventListener("change", filtrarCursos);
    formFiltros.addEventListener("reset", () => window.setTimeout(filtrarCursos));
    filtrarCursos();

    function posicionarOnda() {
        if (!wave || !conteudoCursos) return;

        wave.classList.toggle(
            "cursos-wave--at-end",
            conteudoCursos.getBoundingClientRect().bottom <= window.innerHeight
        );
        frameAgendado = false;
    }

    function agendarPosicionamentoOnda() {
        if (frameAgendado) return;
        frameAgendado = true;
        window.requestAnimationFrame(posicionarOnda);
    }

    posicionarOnda();
    window.addEventListener("scroll", agendarPosicionamentoOnda, { passive: true });
    window.addEventListener("resize", agendarPosicionamentoOnda);
});
