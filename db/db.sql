-- MySQL Script generated by MySQL Workbench
-- Fri Feb 23 13:34:18 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema db_ecommerce
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_ecommerce
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_ecommerce` DEFAULT CHARACTER SET utf8 ;
USE `db_ecommerce` ;

-- -----------------------------------------------------
-- Table `endereco`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `endereco` ;

CREATE TABLE IF NOT EXISTS `endereco` (
  `idendereco` INT NOT NULL AUTO_INCREMENT,
  `estado` VARCHAR(2) NOT NULL,
  `cidade` VARCHAR(45) NOT NULL,
  `bairro` VARCHAR(45) NOT NULL,
  `rua` VARCHAR(45) NOT NULL,
  `numero` VARCHAR(10) NOT NULL,
  `complemento` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`idendereco`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `sobrenome` VARCHAR(45) NOT NULL,
  `CPF` VARCHAR(11) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(45) NOT NULL,
  `tel` VARCHAR(15) NULL,
  `dt_cad` TIMESTAMP NULL,
  `tipo_user` TINYINT NOT NULL,
  `endereco_idendereco` INT NOT NULL,
  PRIMARY KEY (`iduser`),
  CONSTRAINT `fk_cliente_endereco1`
    FOREIGN KEY (`endereco_idendereco`)
    REFERENCES `endereco` (`idendereco`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `CPF_UNIQUE` ON `user` (`CPF` ASC);

CREATE UNIQUE INDEX `dt_cad_UNIQUE` ON `user` (`dt_cad` ASC);

CREATE INDEX `fk_cliente_endereco1_idx` ON `user` (`endereco_idendereco` ASC);


-- -----------------------------------------------------
-- Table `categoria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `categoria` ;

CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` INT NOT NULL AUTO_INCREMENT,
  `des_cat` VARCHAR(256) NULL,
  `dt_cad` TIMESTAMP NOT NULL,
  PRIMARY KEY (`idcategoria`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `produto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `produto` ;

CREATE TABLE IF NOT EXISTS `produto` (
  `idproduto` INT NOT NULL AUTO_INCREMENT,
  `nome_prod` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(256) NOT NULL,
  `preco` FLOAT NOT NULL,
  `dt_cad` TIMESTAMP NOT NULL,
  `categoria_idcategoria` INT NOT NULL,
  PRIMARY KEY (`idproduto`),
  CONSTRAINT `fk_produto_categoria`
    FOREIGN KEY (`categoria_idcategoria`)
    REFERENCES `categoria` (`idcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_produto_categoria_idx` ON `produto` (`categoria_idcategoria` ASC);


-- -----------------------------------------------------
-- Table `carrinho`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `carrinho` ;

CREATE TABLE IF NOT EXISTS `carrinho` (
  `idcarrinho` INT NOT NULL AUTO_INCREMENT,
  `des_sessao` VARCHAR(45) NOT NULL,
  `dt_carrinho` TIMESTAMP NOT NULL,
  `cliente_idcliente` INT NOT NULL,
  PRIMARY KEY (`idcarrinho`),
  CONSTRAINT `fk_carrinho_cliente1`
    FOREIGN KEY (`cliente_idcliente`)
    REFERENCES `user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = '	';

CREATE INDEX `fk_carrinho_cliente1_idx` ON `carrinho` (`cliente_idcliente` ASC);


-- -----------------------------------------------------
-- Table `pedido`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pedido` ;

CREATE TABLE IF NOT EXISTS `pedido` (
  `idpedido` INT NOT NULL AUTO_INCREMENT,
  `dt_pedido` TIMESTAMP NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  `carrinho_idcarrinho` INT NOT NULL,
  PRIMARY KEY (`idpedido`),
  CONSTRAINT `fk_pedido_carrinho1`
    FOREIGN KEY (`carrinho_idcarrinho`)
    REFERENCES `carrinho` (`idcarrinho`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_pedido_carrinho1_idx` ON `pedido` (`carrinho_idcarrinho` ASC);


-- -----------------------------------------------------
-- Table `PC`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `PC` ;

CREATE TABLE IF NOT EXISTS `PC` (
  `produto_idproduto` INT NOT NULL,
  `carrinho_idcarrinho` INT NOT NULL,
  PRIMARY KEY (`produto_idproduto`, `carrinho_idcarrinho`),
  CONSTRAINT `fk_produto_has_carrinho_produto1`
    FOREIGN KEY (`produto_idproduto`)
    REFERENCES `produto` (`idproduto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_has_carrinho_carrinho1`
    FOREIGN KEY (`carrinho_idcarrinho`)
    REFERENCES `carrinho` (`idcarrinho`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_produto_has_carrinho_carrinho1_idx` ON `PC` (`carrinho_idcarrinho` ASC);

CREATE INDEX `fk_produto_has_carrinho_produto1_idx` ON `PC` (`produto_idproduto` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
