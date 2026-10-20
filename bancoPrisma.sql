-- ============================================================
-- Banco: prisma  (versão corrigida e portátil)
-- Compatível com MySQL 5.7+, MySQL 8.x e MariaDB 10.x
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `prisma`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `prisma`;

-- Remove na ordem inversa das dependências
DROP TABLE IF EXISTS `itenspedidos`;
DROP TABLE IF EXISTS `pedidos`;
DROP TABLE IF EXISTS `cursos`;
DROP TABLE IF EXISTS `produtos`;
DROP TABLE IF EXISTS `usuarios`;

-- ------------------------------------------------------------
-- usuarios (sem dependências)
-- ------------------------------------------------------------
CREATE TABLE `usuarios` (
  `id`    INT NOT NULL AUTO_INCREMENT,
  `nome`  VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_usuarios_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- produtos (sem dependências)
-- ------------------------------------------------------------
CREATE TABLE `produtos` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `estoque`     INT DEFAULT 0,
  `categoria`   VARCHAR(30)  NOT NULL,
  `nome`        VARCHAR(40)  NOT NULL,
  `descricao`   VARCHAR(500) DEFAULT NULL,
  `conteudo`    VARCHAR(20)  NOT NULL,
  `preco`       VARCHAR(20)  NOT NULL,
  `Indicacao`   VARCHAR(40)  DEFAULT NULL,
  `cuidados`    VARCHAR(200) DEFAULT NULL,
  `validade`    VARCHAR(30)  NOT NULL,
  `informacoes` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- cursos (depende de produtos)
-- ------------------------------------------------------------
CREATE TABLE `cursos` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `nome`       VARCHAR(150) NOT NULL,
  `nivel`      VARCHAR(50)  NOT NULL,
  `cargaH`     INT NOT NULL,
  `preco`      INT NOT NULL,
  `objetivo`   VARCHAR(255) DEFAULT NULL,
  `conteudo`   VARCHAR(300) DEFAULT NULL,
  `PublicoAlvo` VARCHAR(100) DEFAULT NULL,
  `id_produto` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cursos_produtos` (`id_produto`),
  CONSTRAINT `FK_cursos_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- pedidos (depende de usuarios)
-- ------------------------------------------------------------
CREATE TABLE `pedidos` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `dataHora`     DATETIME NOT NULL,
  `statusPag`    VARCHAR(50) NOT NULL,
  `statusPedido` VARCHAR(50) NOT NULL,
  `valorTotal`   INT NOT NULL,
  `usuario_id`   INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pedidos_usuarios` (`usuario_id`),
  CONSTRAINT `FK_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- itenspedidos (depende de pedidos e produtos)
-- ------------------------------------------------------------
CREATE TABLE `itenspedidos` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `pedido_id`    INT DEFAULT NULL,
  `produto_id`   INT DEFAULT NULL,
  `quantidade`   INT DEFAULT NULL,
  `precoUnidade` INT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_itenspedidos_pedidos` (`pedido_id`),
  KEY `FK_itenspedidos_produtos` (`produto_id`),
  CONSTRAINT `FK_itenspedidos_pedidos`  FOREIGN KEY (`pedido_id`)  REFERENCES `pedidos`  (`id`),
  CONSTRAINT `FK_itenspedidos_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Dados
-- ------------------------------------------------------------
INSERT IGNORE INTO `produtos`
  (`id`, `estoque`, `categoria`, `nome`, `descricao`, `conteudo`, `preco`, `Indicacao`, `cuidados`, `validade`, `informacoes`)
VALUES
  (3, 1, 'Preparação de pele', 'nalu', '', '22 g',  '2', '', '', '12 dias', ''),
  (4, 0, 'Pele', 'João Victor Souza Soares', '', '011 g', '2', '', '', '12 dias', '');

INSERT IGNORE INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
  (9, 'João Victor Souza Soares', 'soaresjoaovictor252@gmail.com', 'joao');

-- Mantém o próximo AUTO_INCREMENT igual ao banco original
ALTER TABLE `produtos` AUTO_INCREMENT = 5;
ALTER TABLE `usuarios` AUTO_INCREMENT = 10;

SET FOREIGN_KEY_CHECKS = 1;