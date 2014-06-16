SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `wkf_task` ;
CREATE SCHEMA IF NOT EXISTS `wkf_task` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `wkf_task` ;

-- -----------------------------------------------------
-- Table `wkf_task`.`usergroup`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`usergroup` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `level` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wkf_task`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `mail` VARCHAR(255) NULL ,
  `password` VARCHAR(255) NULL ,
  `work_time_per_week` INT NULL ,
  `usergroup_id` INT NOT NULL ,
  `notification_enabled` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_user_usergroup1_idx` (`usergroup_id` ASC) ,
  CONSTRAINT `fk_user_usergroup1`
    FOREIGN KEY (`usergroup_id` )
    REFERENCES `wkf_task`.`usergroup` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wkf_task`.`priority`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`priority` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wkf_task`.`status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`status` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wkf_task`.`resolution`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`resolution` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wkf_task`.`ticket_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`ticket_type` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wkf_task`.`ticket`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wkf_task`.`ticket` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(10000) NULL ,
  `create_date` DATE NOT NULL ,
  `due_date` DATE NULL ,
  `end_date` DATE NULL ,
  `estimate_time` INT NULL ,
  `worked_time` INT NULL ,
  `priority_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  `resolution_id` INT NOT NULL ,
  `ticket_type_id` INT NOT NULL ,
  `author_user_id` INT NOT NULL ,
  `owner_user_id` INT NOT NULL ,
  `parent_ticket_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ticket_refPriority_idx` (`priority_id` ASC) ,
  INDEX `fk_ticket_refStatus1_idx` (`status_id` ASC) ,
  INDEX `fk_ticket_refResolution1_idx` (`resolution_id` ASC) ,
  INDEX `fk_ticket_refTicketType1_idx` (`ticket_type_id` ASC) ,
  INDEX `fk_ticket_user1_idx` (`author_user_id` ASC) ,
  INDEX `fk_ticket_user2_idx` (`owner_user_id` ASC) ,
  INDEX `fk_ticket_ticket1_idx` (`parent_ticket_id` ASC) ,
  CONSTRAINT `fk_ticket_refPriority`
    FOREIGN KEY (`priority_id` )
    REFERENCES `wkf_task`.`priority` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refStatus1`
    FOREIGN KEY (`status_id` )
    REFERENCES `wkf_task`.`status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refResolution1`
    FOREIGN KEY (`resolution_id` )
    REFERENCES `wkf_task`.`resolution` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refTicketType1`
    FOREIGN KEY (`ticket_type_id` )
    REFERENCES `wkf_task`.`ticket_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user1`
    FOREIGN KEY (`author_user_id` )
    REFERENCES `wkf_task`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user2`
    FOREIGN KEY (`owner_user_id` )
    REFERENCES `wkf_task`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_ticket1`
    FOREIGN KEY (`parent_ticket_id` )
    REFERENCES `wkf_task`.`ticket` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `wkf_task`.`usergroup`
-- -----------------------------------------------------
START TRANSACTION;
USE `wkf_task`;
INSERT INTO `wkf_task`.`usergroup` (`id`, `name`, `level`) VALUES (1, 'Общая', 0);
INSERT INTO `wkf_task`.`usergroup` (`id`, `name`, `level`) VALUES (2, 'Участник', 10);
INSERT INTO `wkf_task`.`usergroup` (`id`, `name`, `level`) VALUES (3, 'Координатор', 20);
INSERT INTO `wkf_task`.`usergroup` (`id`, `name`, `level`) VALUES (4, 'Администратор', 30);

COMMIT;

-- -----------------------------------------------------
-- Data for table `wkf_task`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `wkf_task`;
INSERT INTO `wkf_task`.`user` (`id`, `name`, `mail`, `password`, `work_time_per_week`, `usergroup_id`, `notification_enabled`) VALUES (1, 'test', NULL, NULL, NULL, 4, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `wkf_task`.`priority`
-- -----------------------------------------------------
START TRANSACTION;
USE `wkf_task`;
INSERT INTO `wkf_task`.`priority` (`id`, `name`) VALUES (1, 'Низкий');
INSERT INTO `wkf_task`.`priority` (`id`, `name`) VALUES (2, 'Средний');
INSERT INTO `wkf_task`.`priority` (`id`, `name`) VALUES (3, 'Высокий');
INSERT INTO `wkf_task`.`priority` (`id`, `name`) VALUES (4, 'Блокер');

COMMIT;

-- -----------------------------------------------------
-- Data for table `wkf_task`.`status`
-- -----------------------------------------------------
START TRANSACTION;
USE `wkf_task`;
INSERT INTO `wkf_task`.`status` (`id`, `name`) VALUES (1, 'Открыт');
INSERT INTO `wkf_task`.`status` (`id`, `name`) VALUES (2, 'В работе');
INSERT INTO `wkf_task`.`status` (`id`, `name`) VALUES (3, 'Решен');
INSERT INTO `wkf_task`.`status` (`id`, `name`) VALUES (4, 'Закрыт');

COMMIT;

-- -----------------------------------------------------
-- Data for table `wkf_task`.`resolution`
-- -----------------------------------------------------
START TRANSACTION;
USE `wkf_task`;
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (1, 'Пусто');
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (2, 'Исправлен');
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (3, 'Не нужен');
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (4, 'Дубликат');

COMMIT;

-- -----------------------------------------------------
-- Data for table `wkf_task`.`ticket_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `wkf_task`;
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (1, 'Эпик');
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (2, 'Задача');
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (3, 'Ошибка');

COMMIT;
