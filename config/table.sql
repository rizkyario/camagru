SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema camagru
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `camagru` ;

-- -----------------------------------------------------
-- Schema camagru
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `camagru` DEFAULT CHARACTER SET utf8 ;
USE `camagru` ;

-- -----------------------------------------------------
-- Table `camagru`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `camagru`.`user` (
  `id` INT(30) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `subscribed` BIT(1) NOT NULL DEFAULT 1,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL,
  `deleted` BIT(1) NOT NULL DEFAULT 0,
  `tokenValidated` VARCHAR(100) NULL,
  `tokenLost` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `camagru`.`photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `camagru`.`photo` (
  `id` INT(30) NOT NULL AUTO_INCREMENT,
  `user` INT(30) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL,
  `deleted` BIT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `camagru`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `camagru`.`comment` (
  `id` INT(30) NOT NULL AUTO_INCREMENT,
  `user` INT(30) NOT NULL,
  `photo` INT(30) NOT NULL,
  `message` VARCHAR(255) NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL,
  `deleted` BIT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `camagru`.`like`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `camagru`.`like` (
  `id` INT(30) NOT NULL AUTO_INCREMENT,
  `user` INT(30) NOT NULL,
  `photo` INT(30) NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL,
  `deleted` BIT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;