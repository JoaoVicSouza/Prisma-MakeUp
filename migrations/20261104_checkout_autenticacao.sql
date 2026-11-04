USE `prisma`;

ALTER TABLE `usuarios`
  ADD COLUMN `admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `senha`;

ALTER TABLE `pedidos`
  ADD COLUMN `telefoneDest` VARCHAR(20) NOT NULL DEFAULT '' AFTER `statusPedido`,
  ADD COLUMN `nomeDest` VARCHAR(100) NOT NULL DEFAULT '' AFTER `telefoneDest`,
  ADD COLUMN `endereco` TEXT DEFAULT NULL AFTER `usuario_id`;

ALTER TABLE `itenspedidos`
  MODIFY COLUMN `precoUnidade` DECIMAL(10,2) DEFAULT NULL;
