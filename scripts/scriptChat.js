const perguntas = [
    'Olá! Vamos começar? Nenhuma resposta ficará registrada ou será compartilhada.',
    'Pode me enviar uma foto do seu rosto?',
    'Poderia descrever sua face e tom de pele?',
    'Para qual ocasião você está se preparando?',
    'Qual horário será o evento?',
    'Tem alguma preferência pessoal de maquiagem? Ex: natural, dramática, clássica, etc.',
    'Gostaria de receber sugestões dos nossos produtos para montar o look?'
];

let perguntaAtual = 0;
let respostas = [];
let fotoSelecionada = false;
let imagemSelecionada = null;
let recomendarProdutos = false;

function mostrarControles() {
    const controles = document.getElementById('controles');
    const campo = controles.querySelector('#descricao');
    if (campo) {
        campo.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
    switch (perguntaAtual) {
        case 0:
            controles.innerHTML = `
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-primary">Vamos começar!</button>
                </div>`;
            break;
        case 1:
            controles.innerHTML = `
                <input id="descricao" name="descricao" type="file" accept="image/jpeg,image/png,image/webp" class="form-control" required>
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-primary" onClick="fotoEnviada()">Enviar foto</button>
                    <button type="button" class="btn btn-secondary btn-pular">Pular</button>
                </div>`;
            break;
        case 2:
            controles.innerHTML = `
                <input id="descricao" name="descricao" type="text" class="form-control" placeholder="Ex.: pele clara, olhos castanhos, cabelo loiro" required>
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                    <button type="button" class="btn btn-secondary btn-pular">Pular</button>
                </div>`;
            break;
        case 3:
            controles.innerHTML = `
                <input id="descricao" name="descricao" type="text" class="form-control" placeholder="Ex.: casamento, festa, trabalho" required>
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                    <button type="button" class="btn btn-secondary btn-pular">Pular</button>
                </div>`;
            break;
        case 4:
            controles.innerHTML = `
                <input id="descricao" name="descricao" type="time" class="form-control" required>
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                    <button type="button" class="btn btn-secondary btn-pular">Pular</button>
                </div>`;
            break;
        case 5:
            controles.innerHTML = `
                <input id="descricao" name="descricao" type="text" class="form-control" placeholder="Ex.: natural" required>
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                    <button type="button" class="btn btn-secondary btn-pular">Pular</button>
                </div>`;
            break;
        case 6:
            controles.innerHTML = `
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="submit" class="btn btn-outline-success" data-resposta="Sim">Sim</button>
                    <button type="submit" class="btn btn-outline-secondary" data-resposta="Não">Não</button>
                </div>`;
            break;
        case 7:
            controles.innerHTML = `
                <div class="d-flex justify-content-evenly mt-2">
                    <button type="button" id="reiniciar" class="btn btn-primary">Reiniciar conversa</button>
                </div>`;
            controles.querySelector('#reiniciar').addEventListener('click', reiniciarConversa);
            break;
    }

    // listener do botão Pular (type="button", não dispara com Enter)
    const btnPular = controles.querySelector('.btn-pular');
    if (btnPular) {
        btnPular.addEventListener('click', function () {
            document.getElementById('resposta').dispatchEvent(
                new SubmitEvent('submit', { bubbles: true, cancelable: true, submitter: btnPular })
            );
        });
        btnPular.dataset.pular = 'true';
    }
}

function mostrarPergunta() {
    adicionarMensagem(perguntas[perguntaAtual], 'recebida');
    mostrarControles();
    rolarParaBaixo();
    console.log(perguntaAtual);
}

function rolarParaBaixo() {
    requestAnimationFrame(() => {
        window.scrollTo({
            top: document.documentElement.scrollHeight,
            behavior: 'smooth'
        });
    });
}

function fotoEnviada() {
    fotoSelecionada = true;
}

document.addEventListener('DOMContentLoaded', mostrarPergunta);

document.getElementById('resposta').addEventListener('submit', async function (event) {
    event.preventDefault();

    const campo = document.getElementById('descricao');
    const botaoSelecionado = event.submitter?.dataset.resposta;
    const resposta = event.submitter?.dataset.pular ? 'Pergunta pulada' : perguntaAtual === 0 ? 'Vamos começar!' : botaoSelecionado || (campo.type === 'file'
        ? (campo.files[0]?.name || '')
        : campo.value.trim());

    if (perguntaAtual === 6) {
        recomendarProdutos = botaoSelecionado === 'Sim';
    }

    if (campo?.type === 'file' && campo.files[0]) {
        imagemSelecionada = campo.files[0];
    }

    adicionarMensagem(resposta, 'enviada');
    respostas.push({
        pergunta: perguntas[perguntaAtual],
        resposta: resposta
    });


    perguntaAtual++;
    if (fotoSelecionada) {
        perguntaAtual++;
        fotoSelecionada = false;
    }

    if (perguntaAtual < perguntas.length) {
        mostrarPergunta();
    } else {
        await enviarParaIa();
    }
});

async function enviarParaIa() {
    const controles = document.getElementById('controles');
    const dados = new FormData();
    dados.append('descricao', respostas.map(item =>
        `${item.pergunta}\nResposta: ${item.resposta}`
    ).join('\n\n'));
    dados.append('recomendar_produtos', recomendarProdutos ? 'sim' : 'nao');
    if (imagemSelecionada) {
        dados.append('foto', imagemSelecionada, imagemSelecionada.name);
    }

    controles.innerHTML = '<p class="text-muted"><span class="loading-icon" aria-hidden="true"><i class="bi bi-arrow-repeat"></i></span> Preparando sua recomendação...</p>';
    rolarParaBaixo();

    try {
        const inicioCarregamento = Date.now();
        const respostaApi = await fetch('../scriptsPHP/upload.php', {
            method: 'POST',
            body: dados
        });
        const textoIa = await respostaApi.text();
        const tempoRestante = 500 - (Date.now() - inicioCarregamento);

        if (tempoRestante > 0) {
            await new Promise(resolve => setTimeout(resolve, tempoRestante));
        }

        // tenta converter a resposta da API (texto) em objeto JSON
        // tenta converter a resposta da API (texto) em objeto JSON
        let data;
        try {
            let respostaParseada = JSON.parse(textoIa);

            // Verifica se a resposta está no formato bruto da API do Gemini
            if (respostaParseada.candidates && respostaParseada.candidates[0].content.parts[0].text) {
                // Extrai a string JSON que está dentro do campo 'text'
                let textoReal = respostaParseada.candidates[0].content.parts[0].text;

                // Limpa possíveis formatações de markdown (ex: ```json ... ```) que a IA costuma colocar
                textoReal = textoReal.replace(/```json/g, '').replace(/```/g, '').trim();

                // Converte a string extraída para o objeto JSON final da maquiagem
                data = JSON.parse(textoReal);
            } else {
                // Se o PHP já tiver retornado o JSON limpo
                data = respostaParseada;
            }

        } catch (erroParse) {
            console.error('Erro ao fazer o parse do JSON:', erroParse);
            console.error('Texto recebido:', textoIa);
            adicionarMensagem('Não foi possível obter a recomendação agora.', 'recebida');
            mostrarControlesFinal();
            rolarParaBaixo();
            return;
        }

        // o upload.php pode devolver { erro: "..." } em vez do look completo
        if (data.erro) {
            console.error('Erro retornado pelo upload.php:', data.erro);
            adicionarMensagem('Não foi possível obter a recomendação agora.', 'recebida');
            mostrarControlesFinal();
            rolarParaBaixo();
            return;
        }

        // quebra o JSON em mensagens e envia uma por vez
        const mensagens = jsonParaMensagens(data);
        await enviarMensagensSequencial(mensagens, document.getElementById('chat-container'));

    } catch (erro) {
        adicionarMensagem('Não foi possível obter a recomendação agora.', 'recebida');
    }

    mostrarControlesFinal();
    rolarParaBaixo();
}

function jsonParaMensagens(data) {
    const mensagens = [];

    mensagens.push({
        tipo: 'intro',
        lookName: data.nome_do_look,
        texto: data.analise_inicial
    });

    data.passo_a_passo.forEach((passo, i) => {
        mensagens.push({
            tipo: 'etapa',
            numero: i + 1,
            titulo: passo.etapa,
            texto: passo.instrucao
        });
    });

    if (data.produtos_recomendados.length > 0) {
        mensagens.push({
            tipo: 'produtos',
            produtos: data.produtos_recomendados
        });
    }

    mensagens.push({
        tipo: 'dica',
        texto: data.dica_extra
    });

    return mensagens;
}

// monta o HTML interno de cada bolha, de acordo com o tipo da mensagem
function criarConteudoMensagem(msg) {
    if (msg.tipo === 'intro') {
        return `<strong>${escaparHtml(msg.lookName)}</strong><p class="mb-0 mt-1">${escaparHtml(msg.texto)}</p>`;
    }
    if (msg.tipo === 'etapa') {
        return `<strong>${msg.numero}. ${escaparHtml(msg.titulo)}</strong><p class="mb-0 mt-1">${escaparHtml(msg.texto)}</p>`;
    }
    if (msg.tipo === 'produtos') {
        const itens = msg.produtos.map(p => {
            const produtoId = Number(p.produto_id);
            const linkProduto = Number.isInteger(produtoId) && produtoId > 0
                ? `<a class="btn btn-sm btn-outline-primary mt-2" href="produto.php?id=${produtoId}">Ver produto</a>`
                : '';
            return `<div class="mt-2"><strong>${escaparHtml(p.nome_do_produto)}</strong><br><span class="text-muted" style="font-size:0.85rem;">${escaparHtml(p.motivo)}</span><br>${linkProduto}</div>`;
        }).join('');
        return `<strong>Produtos para este look</strong>${itens}`;
    }
    if (msg.tipo === 'dica') {
        return `<span class="text-muted">💡 ${escaparHtml(msg.texto)}</span>`;
    }
    return '';
}

function escaparHtml(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, caractere => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    })[caractere]);
}

// bolha temporária de "digitando..." exibida entre uma mensagem e outra
function mostrarDigitando(container) {
    const el = document.createElement('div');
    el.id = 'digitando';
    el.className = 'card bg-light text-dark border-0 rounded-4 rounded-start-0 p-3 shadow-sm me-auto mb-3';
    el.style.maxWidth = '75%';
    el.innerHTML = '<span class="text-muted">digitando...</span>';
    container.appendChild(el);
    rolarParaBaixo();
}

function removerDigitando(container) {
    document.getElementById('digitando')?.remove();
}

// envia as mensagens uma de cada vez, com uma pequena pausa entre elas
async function enviarMensagensSequencial(mensagens, container) {
    for (const msg of mensagens) {
        mostrarDigitando(container);
        await esperar(700 + Math.random() * 500); // 700–1200ms, parece mais natural
        removerDigitando(container);
        adicionarMensagem(criarConteudoMensagem(msg), 'recebida');
        rolarParaBaixo();
    }
}

function esperar(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function mostrarControlesFinal() {
    perguntaAtual = 7;
    mostrarControles();
}

function reiniciarConversa() {
    perguntaAtual = 0;
    respostas = [];
    document.getElementById('chat-container').innerHTML = '';
    mostrarPergunta();
}

function adicionarMensagem(texto, tipo) {
    const chatContainer = document.getElementById('chat-container');
    let msg = '';

    if (tipo === 'enviada') {
        msg = `
            <div class="card bg-success text-white border-0 rounded-4 rounded-end-0 rounded-end-lg-4 p-3 shadow-sm ms-auto mb-3" style="max-width: 75%;">
              <p class="mb-1 text-break">${texto}</p>
            </div>`;

    } else {
        msg = `
            <div class="card bg-light text-dark border-0 rounded-4 rounded-start-0 p-3 shadow-sm me-auto mb-3" style="max-width: 75%;">
              <p class="mb-1 text-break">${texto}</p>
            </div>`;
    }
    chatContainer.insertAdjacentHTML('beforeend', msg);
}