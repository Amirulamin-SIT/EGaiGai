-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `iduser` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(256) NOT NULL,
  `password_hash` VARCHAR(90) NOT NULL,
  `salt` VARCHAR(45) NULL DEFAULT NULL,
  `username` VARCHAR(45) NULL DEFAULT NULL,
  `verified` TINYINT(4) NULL DEFAULT '0',
  `login_fail_amt` INT(11) NULL DEFAULT '0',
  `token` VARCHAR(90) NULL DEFAULT NULL,
  `token_time` DATETIME NULL DEFAULT NULL,
  `disabled` TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`store`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`store` (
  `storeid` INT(11) NOT NULL AUTO_INCREMENT,
  `user_iduser` INT(11) NOT NULL,
  `store_name` VARCHAR(45) NULL DEFAULT NULL,
  `disabled` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`storeid`),
  UNIQUE INDEX `store_name_UNIQUE` (`store_name` ASC) ,
  INDEX `fk_store_user_idx` (`user_iduser` ASC) ,
  CONSTRAINT `fk_store_user`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`item` (
  `iditem` INT(11) NOT NULL AUTO_INCREMENT,
  `store_storeid` INT(11) NOT NULL,
  `item_Name` VARCHAR(45) NULL DEFAULT NULL,
  `description` VARCHAR(280) NULL DEFAULT NULL,
  `price` DECIMAL(6,3) NULL DEFAULT '0.000',
  `quantity` INT(11) NULL DEFAULT '0',
  `disabled` TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY (`iditem`),
  INDEX `fk_item_store1_idx` (`store_storeid` ASC) ,
  CONSTRAINT `fk_item_store1`
    FOREIGN KEY (`store_storeid`)
    REFERENCES `mydb`.`store` (`storeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`cart_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`cart_item` (
  `item_iditem` INT(11) NOT NULL,
  `user_iduser` INT(11) NOT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  INDEX `fk_cart_items_item1_idx` (`item_iditem` ASC) ,
  INDEX `fk_cart_items_user1_idx` (`user_iduser` ASC) ,
  CONSTRAINT `fk_cart_items_item1`
    FOREIGN KEY (`item_iditem`)
    REFERENCES `mydb`.`item` (`iditem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cart_items_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`item_image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`item_image` (
  `iditem_image` INT(11) NOT NULL AUTO_INCREMENT,
  `item_iditem` INT(11) NOT NULL,
  `image_location` VARCHAR(90) NULL DEFAULT NULL,
  PRIMARY KEY (`iditem_image`),
  INDEX `fk_item_image_item1_idx` (`item_iditem` ASC) ,
  CONSTRAINT `fk_item_image_item1`
    FOREIGN KEY (`item_iditem`)
    REFERENCES `mydb`.`item` (`iditem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`order` (
  `order_no` INT(11) NOT NULL AUTO_INCREMENT,
  `user_iduser` INT(11) NOT NULL,
  `date` DATETIME NOT NULL,
  `delivery_address` VARCHAR(256) NULL DEFAULT NULL,
  PRIMARY KEY (`order_no`, `date`),
  INDEX `fk_orders_user1_idx` (`user_iduser` ASC) ,
  CONSTRAINT `fk_orders_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`order_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`order_item` (
  `item_iditem` INT(11) NOT NULL,
  `orders_order_no` INT(11) NOT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  `item_price` DECIMAL(6,3) NULL DEFAULT NULL,
  `status` VARCHAR(45) NULL DEFAULT NULL,
  INDEX `fk_order_items_item1_idx` (`item_iditem` ASC) ,
  INDEX `fk_order_items_orders1_idx` (`orders_order_no` ASC) ,
  CONSTRAINT `fk_order_items_item1`
    FOREIGN KEY (`item_iditem`)
    REFERENCES `mydb`.`item` (`iditem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_items_orders1`
    FOREIGN KEY (`orders_order_no`)
    REFERENCES `mydb`.`order` (`order_no`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
