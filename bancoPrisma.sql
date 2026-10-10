-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24/09/2026 às 18:22
-- Versão do servidor: 8.4.7
-- Versão do PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `prisma`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

DROP TABLE IF EXISTS `cursos`;
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itenspedidos`
--

DROP TABLE IF EXISTS `itenspedidos`;
CREATE TABLE IF NOT EXISTS `itenspedidos` (
  `id` int NOT NULL,
  `pedido_id` int DEFAULT NULL,
  `produto_id` int DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `precoUnidade` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_itenspedidos_pedidos` (`pedido_id`),
  KEY `FK_itenspedidos_produtos` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dataHora` datetime NOT NULL,
  `statusPag` varchar(50) NOT NULL,
  `statusPedido` varchar(50) NOT NULL,
  `valorTotal` int NOT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pedidos_usuarios` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estoque` int DEFAULT (0),
  `categoria` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nome` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descricao` varchar(500) DEFAULT NULL,
  `conteudo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `preco` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Indicacao` varchar(40) DEFAULT NULL,
  `cuidados` varchar(200) DEFAULT NULL,
  `validade` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `informacoes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `estoque`, `categoria`, `nome`, `descricao`, `conteudo`, `preco`, `Indicacao`, `cuidados`, `validade`, `informacoes`) VALUES
(3, 35, 'Preparação de pele', 'Bruma Fixadora Prisma Fresh', 'Bruma multifuncional para refrescar a pele e auxiliar na fixação da maquiagem ao longo do dia.', '120 ml', '34,90', 'Borrifar de 2 a 4 vezes a 20–30 cm do ro', 'Uso externo. Evitar contato direto com os olhos e não borrifar sobre pele irritada ou lesionada. Aplicar em ambiente ventilado e não utilizar próximo a fontes de calor ou chamas. Manter a embalagem fe', '12 dias após aberto', 'Spray fino; pode ser usada antes, durante ou depois da maquiagem.'),
(4, 86, 'Preparação de pele', 'Primer Facial Prisma Glow', 'Primer facial de textura leve que ajuda a uniformizar a aparência da pele e prepara o rosto para a maquiagem.', '30 ml', '39,90', 'Aplicar aproximadamente 1 ervilha no ros', 'Uso externo. Aplicar sobre a pele limpa e hidratada. Evitar contato com os olhos e não utilizar sobre pele irritada ou lesionada. Manter a embalagem bem fechada, protegida do calor, da luz direta e fo', '12 messes após aberto', 'Textura gel; acabamento natural; uso antes da base; aplicação no rosto limpo.'),
(5, 43, 'Pele', 'Base Líquida Prisma Skin', 'Base líquida de cobertura média construível, desenvolvida para proporcionar aparência uniforme e acabamento confortável.', '30 ml', '59,90', 'Aplicar 1 a 2 pumps no rosto inteiro. Us', 'Uso externo. Escolher o tom adequado e aplicar sobre a pele limpa e preparada. Evitar contato com os olhos e não utilizar sobre áreas irritadas ou lesionadas. Manter a embalagem fechada, longe do calo', '12 messes após aberto', 'Cobertura média; acabamento natural; disponível em vários tons; aplicação com esponja, pincel ou dedos.'),
(6, 52, 'Pele', 'Corretivo Líquido Prisma Cover', 'Corretivo de textura cremosa para ajudar a uniformizar visualmente a região abaixo dos olhos e pequenas imperfeições.', '6 ml', '39,90', 'Aplicar cerca de 1 gota por região abaix', 'Uso externo. Aplicar pequenas quantidades e espalhar suavemente. Evitar contato direto com os olhos e não utilizar sobre pele irritada ou lesionada. Não compartilhar o aplicador. Manter bem fechado, p', '12 messes após aberto', 'Cobertura média; textura cremosa; acabamento natural; vários tons.'),
(7, 60, 'Pele', 'Pó Compacto Prisma Matte', 'Pó compacto para auxiliar no controle do brilho e finalizar a maquiagem.', '10 g', '42,90', 'Aplicar uma camada fina com pincel ou es', 'Uso externo. Aplicar com pincel ou esponja limpa e evitar a inalação do pó durante a aplicação. Não compartilhar aplicadores. Evitar contato com os olhos e não utilizar sobre pele irritada ou lesionad', '24 messes após a fabricação', 'Textura fina; acabamento matte; embalagem compacta com espelho.'),
(8, 58, 'Pele', 'Blush Compacto Prisma Blush', 'Blush em pó para adicionar cor e dimensão às maçãs do rosto.', '6 g', '32,90', 'Aplicar 1 a 2 toques em cada maçã do ros', 'Uso externo. Aplicar com pincel limpo e espalhar suavemente nas maçãs do rosto. Evitar contato com os olhos, boca e narinas durante a aplicação. Não utilizar sobre pele irritada ou lesionada e não com', '24 messes após a fabricação', 'Alta possibilidade de construção de intensidade; acabamento acetinado; diferentes tonalidades.'),
(9, 194000, 'Pele', 'Contorno Compacto Prisma Sculpt', 'Produto em pó para criar efeito de dimensão e definição visual no rosto.', '8 g', '36,90', 'Aplicar pequena quantidade nas áreas des', 'Aplicar com pincel e esfumar bem.', '24 messes após a fabricação', 'Textura macia; acabamento natural; tons de contorno em diferentes profundidades.'),
(10, 45, 'Pele', 'Iluminador Prisma Shine', 'Iluminador compacto para proporcionar pontos de luminosidade à maquiagem.', '7 g', '37,90', 'Aplicar 1 a 2 toques nas áreas altas do ', 'Uso externo. Aplicar com pincel ou aplicador limpo nas áreas desejadas do rosto. Evitar contato direto com os olhos e não utilizar sobre pele irritada ou lesionada. Não compartilhar o produto ou os ap', '24 messes após a fabricação', 'Partículas finas; brilho construível; acabamento luminoso.'),
(11, 32, 'Olhos', 'Paleta de Sombras Prisma Sunset', 'Paleta com sombras de acabamento matte e cintilante para criar diferentes combinações de maquiagem.', '18 g', '69,90', 'Usar pequena quantidade de cada sombra e', 'Uso externo. Aplicar somente na região dos olhos, utilizando pincéis limpos. Não utilizar sobre pálpebras irritadas ou lesionadas. Evitar compartilhar a paleta e os aplicadores. Manter fechada, em loc', '24 meses após fabricação', '12 tonalidades; mistura de acabamentos; espelho interno.'),
(12, 67, 'Olhos', 'Máscara de Cílios Prisma Volume', 'Máscara para realçar visualmente os cílios, proporcionando aparência de maior volume.', '10 ml', '34,90', 'Aplicar da raiz às pontas dos cílios em ', 'Uso externo. Aplicar cuidadosamente da raiz às pontas dos cílios, evitando encostar o aplicador nos olhos. Não compartilhar a máscara. Não adicionar água, saliva ou outros líquidos ao produto. Fechar ', '6 meses após aberto', 'Escova aplicadora; efeito de volume; fórmula de fácil construção.'),
(13, 50, 'Olhos', 'Delineador Líquido Prisma Line', 'Delineador líquido para criar linhas definidas e diferentes estilos de maquiagem nos olhos.', '1 ml', '29,90', 'Aplicar uma linha fina rente aos cílios.', 'Uso externo. Aplicar somente na linha externa dos cílios, evitando o contato direto com o globo ocular. Não compartilhar o delineador. Fechar imediatamente após o uso para evitar o ressecamento. Mante', '6 meses após aberto', 'Ponta aplicadora precisa; secagem rápida; acabamento intenso.'),
(14, 40, 'Olhos', 'Lápis de Olhos Prisma Black', 'Lápis macio para contornar e definir os olhos.', '1.2 g', '24,90', 'Aplicar suavemente na linha dos cílios c', 'Uso externo. Aplicar com pouca pressão e somente na área indicada para os olhos. Não compartilhar o lápis. Manter a tampa fechada após o uso e apontar apenas com apontador limpo e higienizado. Evitar ', '24 meses após fabricação', 'Textura macia; cor intensa; pode ser esfumado.'),
(15, 45, 'Sobrancelhas', 'Lápis para Sobrancelhas Prisma Brow', 'Lápis para preencher visualmente e definir o formato das sobrancelhas.', '0.4 g', '27,90', 'Preencher com pequenos traços, cerca de ', 'Uso externo. Aplicar com movimentos leves, realizando pequenos traços para preencher e definir as sobrancelhas. Evitar contato direto com os olhos e não aplicar sobre pele irritada ou lesionada. Não c', '24 meses após fabricação', 'Ponta fina; escovinha integrada; tons variados.'),
(16, 42, 'Sobrancelhas', 'Gel de Sobrancelhas Prisma glow', 'Gel desenvolvido para pentear, alinhar e fixar os fios das sobrancelhas, proporcionando um acabamento organizado e natural. Pode ser utilizado sozinho ou após o preenchimento com lápis ou sombra. ', '5 g', '24,90', 'Aplicar uma pequena quantidade sobre as ', 'Uso externo. Não ingerir. Evitar contato direto com os olhos. Não aplicar sobre pele irritada ou lesionada. Não compartilhar o aplicador. Manter a embalagem bem fechada, protegida do calor, da luz dir', '24 meses / 6M após aberto ', 'Textura em gel, aplicação com escovinha, acabamento natural e fixação flexível. Pode ser usado sozinho ou como finalizador de outros produtos para sobrancelhas. '),
(17, 35, 'Lábios', 'Batom Cremoso Prisma Lips', 'Batom de textura cremosa para adicionar cor aos lábios com sensação confortável.', '3.8 g', '29,90', 'Aplicar diretamente nos lábios em 1 ou 2', 'Uso externo. Aplicar diretamente nos lábios limpos e secos. Não compartilhar o batom. Evitar utilizar sobre lábios irritados, lesionados ou com ferimentos. Manter fechado, protegido do calor e da luz ', '24 meses após fabricação', 'Textura cremosa; acabamento confortável; diferentes tonalidades.'),
(18, 52, 'Lábios', 'Gloss Labial Prisma Glass', 'Gloss para proporcionar brilho aos lábios e complementar diferentes estilos de maquiagem.', '5 ml', '27,90', 'Aplicar 1 a 2 camadas finas nos lábios. ', 'Uso externo. Aplicar uma camada fina nos lábios, utilizando o aplicador limpo. Não compartilhar o aplicador. Evitar contato com os olhos e não aplicar sobre lábios irritados ou lesionados. Manter fech', '12 meses após aberto', 'Brilho intenso; textura confortável; aplicador integrado.'),
(19, 60, 'Lábios', 'Lip Tint Prisma Color', 'Tint para adicionar coloração suave e construível aos lábios.', '4 ml', '26,90', 'Aplicar 1 a 2 gotas e espalhar. Usar em ', 'Uso externo. Aplicar uma pequena quantidade nos lábios e espalhar uniformemente. Não compartilhar o produto ou o aplicador. Evitar aplicar sobre lábios irritados, ressecados ou lesionados. Manter fech', '12 meses após aberto', 'Pigmentação construível; textura leve; acabamento natural.'),
(20, 54, 'Acessórios', 'Esponja de Maquiagem Prisma Soft', 'Esponja macia para aplicação e acabamento de produtos líquidos e cremosos.', '1 unidade', '19,90', 'Umedecer levemente, retirar o excesso de', 'Higienizar antes e depois do uso com produto adequado e deixar secar completamente em local ventilado. Não guardar úmida ou dentro de recipiente fechado. Não compartilhar sem higienização. Substituir ', 'Recomenda-se substituição peri', 'Formato anatômico; pode ser usada seca ou levemente umedecida; lavável.'),
(21, 65, 'Acessórios', 'Kit de Pincéis Prisma Essential', 'Conjunto de pincéis para preparação, aplicação e acabamento da maquiagem.', '8 unidade', '79,90', 'Usar o pincel adequado para cada etapa, ', 'Higienizar os pincéis regularmente com produto apropriado e deixar secar completamente antes de guardar. Não armazenar úmidos. Não compartilhar sem higienização. Guardar em local limpo, seco e ventila', 'Recomenda-se substituição conf', '8 pincéis; cerdas macias; cabo ergonômico; estojo incluso.'),
(22, 33, 'Acessórios', 'Necessaire Prisma Beauty', 'Necessaire para armazenar e transportar produtos de maquiagem e cuidados pessoais.', '1 unidade', '44,90', 'Armazenar os produtos sem excesso de pes', 'Manter limpa, seca e organizada. Evitar guardar produtos abertos, vazando ou úmidos por longos períodos. Não sobrecarregar o zíper ou os compartimentos. Limpar conforme o material da necessaire e deix', 'Uso prolongado conforme conser', 'Compartimento principal; fechamento por zíper; material de fácil limpeza.'),
(23, 144, 'Kits', 'Kit Prisma Everyday', 'Kit com produtos básicos para montar uma maquiagem prática para o dia a dia.', '5 item', '149,90', 'Aplicar os itens em pequenas quantidades', 'Uso externo. Conferir os cuidados específicos de cada produto presente no kit. Não compartilhar maquiagens, pincéis ou aplicadores. Manter os itens fechados, longe do calor, da luz direta e da umidade', 'Conforme validade de cada item', 'Primer, base, corretivo, blush e gloss; composição pode variar conforme disponibilidade.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `endereco`) VALUES
(9, 'João Victor Souza Soares', 'soaresjoaovictor252@gmail.com', 'joao', NULL);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itenspedidos`
--
ALTER TABLE `itenspedidos`
  ADD CONSTRAINT `FK_itenspedidos_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `FK_itenspedidos_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `FK_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
