<?php
header('Content-Type: application/json; charset=utf-8');

function responderErro(string $mensagem, int $statusHttp = 500): void
{
    http_response_code($statusHttp);
    echo json_encode(['erro' => $mensagem], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderErro('Método não permitido.', 405);
}

$arquivoConfiguracaoOpenAI = __DIR__ . '/openai-config.php';
if (!is_file($arquivoConfiguracaoOpenAI)) {
    error_log('O arquivo scriptsPHP/openai-config.php não está configurado.');
    responderErro('A integração com a IA não está configurada no servidor.', 503);
}

$apiKey = require $arquivoConfiguracaoOpenAI;
if (!is_string($apiKey) || trim($apiKey) === '') {
    error_log('A chave OpenAI não está configurada em scriptsPHP/openai-config.php.');
    responderErro('Configure a chave da OpenAI em scriptsPHP/openai-config.php.', 503);
}

$descricao = $_POST['descricao'] ?? '';
if (!is_string($descricao)) {
    responderErro('Os dados enviados são inválidos.', 400);
}
$descricao = trim($descricao);

$opcaoRecomendacao = $_POST['recomendar_produtos'] ?? null;
if (!is_string($opcaoRecomendacao) || !in_array($opcaoRecomendacao, ['sim', 'nao'], true)) {
    $perguntaRecomendacao = preg_quote('Gostaria de receber sugestões dos nossos produtos para montar o look?', '/');
    if (preg_match('/' . $perguntaRecomendacao . '\s*\RResposta:\s*(Sim|Não)\s*$/u', $descricao, $correspondencia)) {
        $opcaoRecomendacao = mb_strtolower($correspondencia[1], 'UTF-8') === 'sim' ? 'sim' : 'nao';
    }
}
if (!is_string($opcaoRecomendacao) || !in_array($opcaoRecomendacao, ['sim', 'nao'], true)) {
    responderErro('Informe se deseja receber sugestões de produtos.', 400);
}
$recomendarProdutos = $opcaoRecomendacao === 'sim';

$produtos = [];
$produtoIdsPorNome = [];
if ($recomendarProdutos) {
    require 'conexao.php';

    try {
        $stmt = $pdo->query(
            'SELECT id, nome
             FROM produtos
             WHERE estoque > 0'
        );
        $produtosDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produtosDisponiveis as $produto) {
            $produtos[] = $produto['nome'];
            $produtoIdsPorNome[mb_strtolower(trim($produto['nome']), 'UTF-8')] = (int) $produto['id'];
        }
    } catch (PDOException $erro) {
        error_log('Erro ao consultar produtos para recomendação: ' . $erro->getMessage());
        responderErro('Não foi possível carregar o catálogo de produtos.');
    }
}

$conteudoUsuario = [
    [
        'type' => 'input_text',
        'text' => "DADOS RESPONDIDOS PELO USUÁRIO:\n" . $descricao
            . "\n\nSugestões de produtos autorizadas: " . ($recomendarProdutos ? 'sim' : 'não')
            . "\nFormato de resposta solicitado: JSON válido.",
    ],
];

$upload = $_FILES['foto'] ?? null;
if (is_array($upload) && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($upload['error'] !== UPLOAD_ERR_OK) {
        if ($upload['error'] === UPLOAD_ERR_INI_SIZE || $upload['error'] === UPLOAD_ERR_FORM_SIZE) {
            responderErro('A imagem excede o tamanho máximo permitido.', 413);
        }
        error_log('Falha ao receber imagem para recomendação. Código de upload: ' . $upload['error']);
        responderErro('Não foi possível receber a imagem.', 400);
    }

    if ($upload['size'] <= 0 || $upload['size'] > 5 * 1024 * 1024 || !is_uploaded_file($upload['tmp_name'])) {
        responderErro('Envie uma imagem válida de até 5 MB.', 400);
    }

    $tiposImagemPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $tipoMime = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
    if (!is_string($tipoMime) || !in_array($tipoMime, $tiposImagemPermitidos, true)) {
        responderErro('Envie uma imagem JPG, PNG ou WEBP.', 400);
    }

    $dadosBinarios = file_get_contents($upload['tmp_name']);
    if ($dadosBinarios === false) {
        error_log('Não foi possível ler a imagem enviada para recomendação.');
        responderErro('Não foi possível processar a imagem enviada.');
    }

    $conteudoUsuario[] = [
        'type' => 'input_image',
        'image_url' => 'data:' . $tipoMime . ';base64,' . base64_encode($dadosBinarios),
    ];
}

$instrucao = '
Você é uma Maquiadora Profissional e Consultora de Beleza Virtual experiente, empática e criativa. Analise as informações fornecidas pelo usuário e crie uma recomendação de maquiagem personalizada, explicando o passo a passo de forma clara e acessível.

### REGRAS DE COMPORTAMENTO:
1. Use um tom encorajador, amigável e profissional.
2. Adapte a maquiagem ao horário e à ocasião informados.
3. Se houver foto ou descrição do rosto, adapte as técnicas aos traços e ao tom de pele informados ou visíveis. Não deduza características que não sejam perceptíveis.
   - Quando uma foto for enviada, observe a imagem e mencione brevemente na análise inicial que ela foi considerada, citando apenas características visíveis. Se não conseguir observar algum detalhe, não invente.
   - Organize o passo a passo em pelo menos seis etapas distintas: preparação e pele, olhos, sobrancelhas, bochechas (blush/contorno/iluminador conforme o look), lábios e finalização/fixação.
   - Em cada etapa, dê instruções práticas e específicas para a ocasião e o estilo escolhidos. Não junte todas as áreas do rosto em apenas "pele" e "olhos".
4. Respeite obrigatoriamente a decisão explícita sobre sugestões de produtos:
   - Se autorizadas, recomende somente produtos do catálogo abaixo e use o nome exato.
   - Se não autorizadas, não recomende nem mencione produtos ou marcas; retorne "produtos_recomendados" como uma lista vazia. Não sugira alternativas genéricas.
5. Catálogo de produtos disponíveis: ' . ($recomendarProdutos ? implode(', ', $produtos) : '(não autorizado; não utilizar)') . '

Responda com um objeto JSON válido contendo exatamente estes campos:
{
  "analise_inicial": "Breve parágrafo explicando por que o look combina com a ocasião e o horário.",
  "nome_do_look": "Nome criativo para a maquiagem.",
  "passo_a_passo": [
    {
      "etapa": "Preparação e pele",
      "instrucao": "Descreva preparação e aplicação de produtos para a pele, adaptadas ao look."
    },
    {
      "etapa": "Olhos",
      "instrucao": "Descreva sombras, delineado e máscara conforme o look."
    },
    {
      "etapa": "Sobrancelhas",
      "instrucao": "Descreva como pentear e preencher as sobrancelhas de forma adequada."
    },
    {
      "etapa": "Bochechas",
      "instrucao": "Descreva blush, contorno ou iluminador conforme a proposta."
    },
    {
      "etapa": "Lábios",
      "instrucao": "Descreva a preparação e a cor ou acabamento dos lábios."
    },
    {
      "etapa": "Finalização",
      "instrucao": "Descreva como finalizar e ajudar a maquiagem a durar."
    }
  ],
  "produtos_recomendados": ' . ($recomendarProdutos
        ? '[{"nome_do_produto":"Nome exato do produto da loja","motivo":"Por que é adequado para o look."}]'
        : '[]') . ',
  "dica_extra": "Uma dica final para o look."
}';

$payload = [
    'model' => 'gpt-4.1-mini',
    'instructions' => $instrucao,
    'input' => [
        [
            'role' => 'user',
            'content' => $conteudoUsuario,
        ],
    ],
    'text' => [
        'format' => [
            'type' => 'json_object',
        ],
    ],
];

$corpoRequisicao = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($corpoRequisicao === false) {
    error_log('Não foi possível codificar a requisição para a API OpenAI.');
    responderErro('Não foi possível preparar a solicitação para a IA.');
}

$ch = curl_init('https://api.openai.com/v1/responses');
if ($ch === false) {
    error_log('Não foi possível inicializar cURL para a API OpenAI.');
    responderErro('Não foi possível conectar à IA.');
}

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $corpoRequisicao,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . trim($apiKey),
        'Content-Type: application/json',
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 90,
]);

$respostaApi = curl_exec($ch);
$erroCurl = curl_error($ch);
$statusHttp = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($respostaApi === false) {
    error_log('Falha de conexão com a API OpenAI: ' . $erroCurl);
    responderErro('Erro de conexão com a IA. Tente novamente.');
}

$resultado = json_decode($respostaApi, true);
if (!is_array($resultado)) {
    error_log('A API OpenAI retornou uma resposta que não é JSON válido.');
    responderErro('A IA retornou uma resposta inválida.');
}

if ($statusHttp < 200 || $statusHttp >= 300) {
    $codigoErro = $resultado['error']['code'] ?? '';
    error_log('Erro da API OpenAI (HTTP ' . $statusHttp . '): ' . json_encode($resultado, JSON_UNESCAPED_UNICODE));

    if ($statusHttp === 429 || $codigoErro === 'insufficient_quota') {
        responderErro('Limite de uso da IA atingido. Tente novamente mais tarde.', 503);
    }

    responderErro('Não foi possível obter uma recomendação da IA. Tente novamente.', 502);
}

$blocosTexto = [];
foreach ($resultado['output'] ?? [] as $item) {
    if (($item['type'] ?? null) !== 'message' || !is_array($item['content'] ?? null)) {
        continue;
    }

    foreach ($item['content'] as $bloco) {
        if (($bloco['type'] ?? null) === 'output_text' && is_string($bloco['text'] ?? null)) {
            $blocosTexto[] = $bloco['text'];
        }
    }
}

$respostaIa = implode("\n", $blocosTexto);
$dadosIa = json_decode($respostaIa, true);
$camposObrigatorios = ['analise_inicial', 'nome_do_look', 'passo_a_passo', 'produtos_recomendados', 'dica_extra'];
if (
    !is_array($dadosIa) ||
    array_diff($camposObrigatorios, array_keys($dadosIa)) !== [] ||
    !is_array($dadosIa['passo_a_passo']) ||
    !is_array($dadosIa['produtos_recomendados'])
) {
    error_log('A API OpenAI não retornou o formato esperado para a recomendação.');
    responderErro('A IA retornou uma recomendação inválida. Tente novamente.');
}

if (!$recomendarProdutos) {
    $dadosIa['produtos_recomendados'] = [];
} else {
    $dadosIa['produtos_recomendados'] = array_values(array_filter(array_map(
        static function ($produto) use ($produtoIdsPorNome) {
            if (!is_array($produto) || !is_string($produto['nome_do_produto'] ?? null)) {
                return null;
            }

            $nome = trim($produto['nome_do_produto']);
            $chaveNome = mb_strtolower($nome, 'UTF-8');
            if (!isset($produtoIdsPorNome[$chaveNome])) {
                return null;
            }

            return [
                'produto_id' => $produtoIdsPorNome[$chaveNome],
                'nome_do_produto' => $nome,
                'motivo' => is_string($produto['motivo'] ?? null) ? $produto['motivo'] : '',
            ];
        },
        $dadosIa['produtos_recomendados']
    )));
}

echo json_encode($dadosIa, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
