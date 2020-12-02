-- MySQL Workbench Forward Engineering
SET GLOBAL event_scheduler = ON;
SET SQL_SAFE_UPDATES = 0;

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
-- Table `mydb`.`item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`item` (
  `iditem` INT(11) NOT NULL AUTO_INCREMENT,
  `item_Name` VARCHAR(45) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` DECIMAL(7,2) NULL DEFAULT '0.000',
  `quantity` INT(11) NULL DEFAULT '0',
  `disabled` TINYINT(4) NULL DEFAULT '0',
  `image_location` VARCHAR(256) NULL,
  PRIMARY KEY (`iditem`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `iduser` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(256) NOT NULL,
  `password_hash` VARCHAR(90) NOT NULL,
  `username` VARCHAR(45) NULL DEFAULT NULL,
  `verified` TINYINT(4) NULL DEFAULT '0',
  `login_fail_amt` INT(11) NULL DEFAULT '0',
  `token` VARCHAR(90) NULL DEFAULT NULL,
  `token_time` DATETIME NULL DEFAULT NULL,
  `disabled` TINYINT(4) NULL DEFAULT '0',
  `type` VARCHAR(10) NULL DEFAULT 'user',
  `hp_num` VARCHAR(15) NULL DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`cart_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`cart_item` (
  `item_iditem` INT(11) NOT NULL,
  `user_iduser` INT(11) NOT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  INDEX `fk_cart_items_item1_idx` (`item_iditem` ASC),
  INDEX `fk_cart_items_user1_idx` (`user_iduser` ASC),
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
-- Table `mydb`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`orders` (
  `order_no` INT(11) NOT NULL AUTO_INCREMENT,
  `user_iduser` INT(11) NOT NULL,
  `date` DATETIME NOT NULL,
  `delivery_address` VARCHAR(256) NULL DEFAULT NULL,
  `delivered` TINYINT(4) NULL DEFAULT '0',
  `timeout` DATETIME NULL DEFAULT NULL,
  `disabled` TINYINT(4) NULL DEFAULT '0',
  `status` VARCHAR(10) NULL DEFAULT 'unknown',
  PRIMARY KEY (`order_no`, `date`),
  INDEX `fk_orders_user1_idx` (`user_iduser` ASC),
  CONSTRAINT `fk_orders_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `mydb`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 49
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `mydb`.`order_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`order_item` (
  `item_iditem` INT(11) NOT NULL,
  `orders_order_no` INT(11) NOT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  `item_price` DECIMAL(6,3) NULL DEFAULT NULL,
  INDEX `fk_order_items_item1_idx` (`item_iditem` ASC),
  INDEX `fk_order_items_orders1_idx` (`orders_order_no` ASC),
  CONSTRAINT `fk_order_items_item1`
    FOREIGN KEY (`item_iditem`)
    REFERENCES `mydb`.`item` (`iditem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_items_orders1`
    FOREIGN KEY (`orders_order_no`)
    REFERENCES `mydb`.`orders` (`order_no`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

USE `mydb` ;

-- -----------------------------------------------------
-- Placeholder table for view `mydb`.`cart_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`cart_view` (`user_iduser` INT, `iditem` INT, `quantity` INT, `price` INT, `item_name` INT, `total_price` INT, `disabled` INT, `available` INT);

-- -----------------------------------------------------
-- Placeholder table for view `mydb`.`checkout_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`checkout_view` (`user_iduser` INT, `iditem` INT, `quantity` INT, `price` INT, `item_name` INT, `total_price` INT, `disabled` INT, `available` INT);

-- -----------------------------------------------------
-- View `mydb`.`cart_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`cart_view`;
USE `mydb`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `mydb`.`cart_view` AS select `mydb`.`cart_item`.`user_iduser` AS `user_iduser`,`mydb`.`cart_item`.`item_iditem` AS `iditem`,`mydb`.`cart_item`.`quantity` AS `quantity`,`mydb`.`item`.`price` AS `price`,`mydb`.`item`.`item_Name` AS `item_name`,(`mydb`.`cart_item`.`quantity` * `mydb`.`item`.`price`) AS `total_price`,`mydb`.`item`.`disabled` AS `disabled`,if((`mydb`.`cart_item`.`quantity` <= `mydb`.`item`.`quantity`),1,0) AS `available` from (`mydb`.`cart_item` join `mydb`.`item` on((`mydb`.`cart_item`.`item_iditem` = `mydb`.`item`.`iditem`)));

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
