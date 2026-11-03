USE `prisma`;

CREATE TABLE `produto_variacoes` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `produto_id` INT NOT NULL,
  `rotulo`     VARCHAR(80) NOT NULL,
  `imagem`     VARCHAR(255) NOT NULL,
  `ordem`      TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_produto_variacoes_ordem` (`produto_id`, `ordem`),
  CONSTRAINT `FK_produto_variacoes_produtos`
    FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `itenspedidos`
  ADD COLUMN `variacao_nome` VARCHAR(80) DEFAULT NULL AFTER `produto_id`;
