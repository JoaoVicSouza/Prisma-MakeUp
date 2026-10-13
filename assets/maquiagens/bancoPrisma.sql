-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para prisma
CREATE DATABASE IF NOT EXISTS `prisma` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `prisma`;

-- Copiando estrutura para tabela prisma.cursos
DROP TABLE IF EXISTS `cursos`;
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela prisma.cursos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela prisma.itenspedidos
DROP TABLE IF EXISTS `itenspedidos`;
CREATE TABLE IF NOT EXISTS `itenspedidos` (
  `id` int NOT NULL,
  `pedido_id` int DEFAULT NULL,
  `produto_id` int DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `precoUnidade` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_itenspedidos_pedidos` (`pedido_id`),
  KEY `FK_itenspedidos_produtos` (`produto_id`),
  CONSTRAINT `FK_itenspedidos_pedidos` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  CONSTRAINT `FK_itenspedidos_produtos` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela prisma.itenspedidos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela prisma.pedidos
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dataHora` datetime NOT NULL,
  `statusPag` varchar(50) NOT NULL,
  `statusPedido` varchar(50) NOT NULL,
  `valorTotal` int NOT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pedidos_usuarios` (`usuario_id`),
  CONSTRAINT `FK_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela prisma.pedidos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela prisma.produtos
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela prisma.produtos: ~2 rows (aproximadamente)
INSERT IGNORE INTO `produtos` (`id`, `estoque`, `categoria`, `nome`, `descricao`, `conteudo`, `preco`, `Indicacao`, `cuidados`, `validade`, `informacoes`) VALUES
	(3, 1, 'Preparação de pele', 'nalu', '', '22 g', '2', '', '', '12 dias', ''),
	(4, 0, 'Pele', 'João Victor Souza Soares', '', '011 g', '2', '', '', '12 dias', '');

-- Copiando estrutura para tabela prisma.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela prisma.usuarios: ~0 rows (aproximadamente)
INSERT IGNORE INTO `usuarios` (`id`, `nome`, `email`, `senha`, `endereco`) VALUES
	(9, 'João Victor Souza Soares', 'soaresjoaovictor252@gmail.com', 'joao', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
