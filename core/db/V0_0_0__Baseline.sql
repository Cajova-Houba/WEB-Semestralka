-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema kiv-web
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `kiv-web` ;

-- -----------------------------------------------------
-- Schema kiv-web
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `kiv-web` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `kiv-web` ;

-- -----------------------------------------------------
-- Table `kiv-web`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`role` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kiv-web`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`user` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(70) NOT NULL,
  `email` VARCHAR(100) NULL,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_role_idx` (`role_id` ASC),
  CONSTRAINT `fk_user_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `kiv-web`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kiv-web`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`article` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `content` LONGTEXT NULL,
  `created` DATE NOT NULL,
  `state` VARCHAR(20) NOT NULL DEFAULT 'CREATED',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kiv-web`.`author`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`author` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`author` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `article_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_author_user1_idx` (`user_id` ASC),
  INDEX `fk_author_article1_idx` (`article_id` ASC),
  CONSTRAINT `fk_author_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `kiv-web`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_author_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `kiv-web`.`article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kiv-web`.`review_result`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`review_result` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`review_result` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `crit_1` INT NOT NULL DEFAULT 0,
  `crit_2` INT NOT NULL DEFAULT 0,
  `crit_3` INT NOT NULL DEFAULT 0,
  `crit_4` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'Results of reviews.';


-- -----------------------------------------------------
-- Table `kiv-web`.`review`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`review` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`review` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `article_id` INT NOT NULL,
  `reviewer_id` INT NOT NULL,
  `assigned_by_id` INT NOT NULL,
  `review_result_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_review_article1_idx` (`article_id` ASC),
  INDEX `fk_review_user1_idx` (`reviewer_id` ASC),
  INDEX `fk_review_user2_idx` (`assigned_by_id` ASC),
  INDEX `fk_review_review_result1_idx` (`review_result_id` ASC),
  CONSTRAINT `fk_review_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `kiv-web`.`article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_user1`
    FOREIGN KEY (`reviewer_id`)
    REFERENCES `kiv-web`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_user2`
    FOREIGN KEY (`assigned_by_id`)
    REFERENCES `kiv-web`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_review_result1`
    FOREIGN KEY (`review_result_id`)
    REFERENCES `kiv-web`.`review_result` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table which assigns articles to reviewers.';


-- -----------------------------------------------------
-- Table `kiv-web`.`tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`tag` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL DEFAULT 'tag',
  `description` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kiv-web`.`article_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`article_tag` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`article_tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `article_id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_article_tag_article1_idx` (`article_id` ASC),
  INDEX `fk_article_tag_tag1_idx` (`tag_id` ASC),
  CONSTRAINT `fk_article_tag_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `kiv-web`.`article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_article_tag_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `kiv-web`.`tag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kiv-web`.`file`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `kiv-web`.`file` ;

CREATE TABLE IF NOT EXISTS `kiv-web`.`file` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(45) NULL,
  `name` VARCHAR(45) NULL,
  `article_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_file_article1_idx` (`article_id` ASC),
  CONSTRAINT `fk_file_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `kiv-web`.`article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
