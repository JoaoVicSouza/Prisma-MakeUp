const CHAVE_CARRINHO = 'carrinhoPrisma';

function obterCarrinho() {
    const carrinhoSalvo = localStorage.getItem(CHAVE_CARRINHO);
    if (carrinhoSalvo === null) return [];

    const carrinho = JSON.parse(carrinhoSalvo);
    if (!Array.isArray(carrinho) || carrinho.some(function (item) {
        return !item ||
            (typeof item.id !== 'string' && typeof item.id !== 'number') ||
            typeof item.nome !== 'string' ||
            !Number.isFinite(Number(item.preco)) ||
            Number(item.preco) < 0 ||
            !Number.isInteger(Number(item.quantidade)) ||
            Number(item.quantidade) < 1;
    })) {
        throw new Error('Os dados salvos do carrinho são inválidos.');
    }

    return carrinho;
}

function salvarCarrinho(carrinho) {
    localStorage.setItem(CHAVE_CARRINHO, JSON.stringify(carrinho));
}

let temporizadorNotificacaoCarrinho;

function mostrarNotificacaoCarrinho(nomeProduto) {
    const notificacao = document.getElementById('notificacaoCarrinho');
    if (!notificacao) return;

    notificacao.textContent = `${nomeProduto} foi adicionado ao carrinho!`;
    notificacao.classList.remove('d-none');

    clearTimeout(temporizadorNotificacaoCarrinho);
    temporizadorNotificacaoCarrinho = setTimeout(function () {
        notificacao.classList.add('d-none');
    }, 3000);
}

function adicionarAoCarrinho(idProduto, nomeProduto, precoProduto, imagemProduto = '') {
    const carrinho = obterCarrinho();
    const produtoExistente = carrinho.find(function (item) {
        return String(item.id) === String(idProduto);
    });

    if (produtoExistente) {
        produtoExistente.quantidade += 1;
        if (imagemProduto) {
            produtoExistente.imagem = imagemProduto;
        }
    } else {
        carrinho.push({
            id: idProduto,
            nome: nomeProduto,
            preco: precoProduto,
            quantidade: 1,
            imagem: imagemProduto
        });
    }

    salvarCarrinho(carrinho);
}

function comprarAgora(idProduto, nomeProduto, precoProduto, imagemProduto) {
    adicionarAoCarrinho(idProduto, nomeProduto, precoProduto, imagemProduto);
    window.location.href = new URL('compra.php', window.location.href).href;
}

function formatarPreco(valor) {
    return Number(valor).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

function escaparHTML(valor) {
    return String(valor).replace(/[&<>"']/g, function (caractere) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[caractere];
    });
}

function renderizarCarrinho() {
    const container = document.getElementById('cartItems');
    if (!container) return;

    const mensagemVazio = document.getElementById('emptyCartMsg');
    const mensagemErro = document.getElementById('cartErrorMsg');
    const subtotalElemento = document.getElementById('subtotal');
    const totalElemento = document.getElementById('total');
    const botaoFinalizar = document.getElementById('submitBtn');

    container.replaceChildren();
    mensagemErro.classList.add('d-none');
    mensagemVazio.classList.add('d-none');

    try {
        const carrinho = obterCarrinho();
        const subtotal = carrinho.reduce(function (soma, item) {
            return soma + Number(item.preco) * Number(item.quantidade);
        }, 0);

        container.innerHTML = carrinho.map(function (item, indice) {
            const preco = Number(item.preco);
            const quantidade = Number(item.quantidade);
            const totalItem = preco * quantidade;
            const nome = escaparHTML(item.nome);
            const imagem = item.imagem
                ? `<img src="${escaparHTML(item.imagem)}" alt="${nome}" class="w-100 h-100 rounded" style="object-fit: cover;">`
                : '<i class="bi bi-bag fs-4" style="color: var(--primary2);"></i>';

            return `
                <div class="cart-item py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="thumb overflow-hidden" style="background-color: var(--primary);">
                            ${imagem}
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold">${nome}</p>
                            <p class="mb-2 text-secondary small">${formatarPreco(preco)} / unidade</p>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary quantity-button" type="button" data-acao-quantidade="diminuir" data-indice="${indice}" aria-label="Diminuir quantidade de ${nome}" ${quantidade <= 1 ? 'disabled' : ''}>
                                    <i class="bi bi-dash"></i>
                                </button>
                                <span aria-label="Quantidade">${quantidade}</span>
                                <button class="btn btn-sm btn-outline-secondary quantity-button" type="button" data-acao-quantidade="aumentar" data-indice="${indice}" aria-label="Aumentar quantidade de ${nome}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn remove-item" type="button" data-acao-quantidade="remover" data-indice="${indice}" aria-label="Remover ${nome} do carrinho">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <p class="mb-0 fw-semibold text-end mt-2">${formatarPreco(totalItem)}</p>
                </div>`;
        }).join('');

        subtotalElemento.textContent = formatarPreco(subtotal);
        totalElemento.textContent = formatarPreco(subtotal);
        mensagemVazio.classList.toggle('d-none', carrinho.length > 0);
        botaoFinalizar.disabled = carrinho.length === 0;
    } catch (erro) {
        console.error('Não foi possível carregar o carrinho do armazenamento local.', erro);
        mensagemErro.textContent = 'Não foi possível carregar o carrinho salvo. Volte à loja e tente novamente.';
        mensagemErro.classList.remove('d-none');
        subtotalElemento.textContent = formatarPreco(0);
        totalElemento.textContent = formatarPreco(0);
        botaoFinalizar.disabled = true;
    }
}

function removerDoCarrinho(idProduto) {
    const carrinho = obterCarrinho().filter(function (item) {
        return String(item.id) !== String(idProduto);
    });
    salvarCarrinho(carrinho);
    renderizarCarrinho();
}

const containerCarrinho = document.getElementById('cartItems');
if (containerCarrinho) {
    containerCarrinho.addEventListener('click', function (evento) {
        const botaoAcao = evento.target.closest('button[data-acao-quantidade]');
        if (!botaoAcao) return;

        try {
            const carrinho = obterCarrinho();
            const indice = Number(botaoAcao.dataset.indice);
            if (!Number.isInteger(indice) || indice < 0 || indice >= carrinho.length) {
                throw new Error('O item selecionado não está mais no carrinho.');
            }

            const acao = botaoAcao.dataset.acaoQuantidade;
            if (acao === 'remover') {
                carrinho.splice(indice, 1);
            } else if (acao === 'aumentar') {
                carrinho[indice].quantidade = Number(carrinho[indice].quantidade) + 1;
            } else if (acao === 'diminuir' && Number(carrinho[indice].quantidade) > 1) {
                carrinho[indice].quantidade = Number(carrinho[indice].quantidade) - 1;
            } else {
                return;
            }

            salvarCarrinho(carrinho);
            renderizarCarrinho();
        } catch (erro) {
            console.error('Não foi possível atualizar a quantidade do produto no carrinho.', erro);
            const mensagemErro = document.getElementById('cartErrorMsg');
            mensagemErro.textContent = 'Não foi possível atualizar o carrinho. Recarregue a página e tente novamente.';
            mensagemErro.classList.remove('d-none');
            document.getElementById('submitBtn').disabled = true;
        }
    });

    renderizarCarrinho();
}

const formularioCheckout = document.getElementById('checkoutForm');
if (formularioCheckout) {
    formularioCheckout.addEventListener('submit', function (evento) {
        try {
            const carrinho = obterCarrinho();
            if (carrinho.length === 0) {
                throw new Error('O carrinho está vazio.');
            }

            document.getElementById('cartData').value = JSON.stringify(carrinho.map(function (item) {
                return { id: item.id, quantidade: item.quantidade };
            }));
        } catch (erro) {
            evento.preventDefault();
            const mensagemErro = document.getElementById('cartErrorMsg');
            mensagemErro.textContent = 'Não foi possível enviar o carrinho. Volte à loja e tente novamente.';
            mensagemErro.classList.remove('d-none');
        }
    });
}

document.querySelectorAll('[data-acao-carrinho]').forEach(function (botao) {
    botao.addEventListener('click', function () {
        const { acaoCarrinho, id, nome, preco, imagem } = botao.dataset;

        if (acaoCarrinho === 'comprar') {
            comprarAgora(id, nome, Number(preco), imagem);
            return;
        }

        adicionarAoCarrinho(id, nome, Number(preco), imagem);
        mostrarNotificacaoCarrinho(nome);
    });
});
