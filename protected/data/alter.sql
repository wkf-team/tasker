-- MySQL Workbench Synchronization
-- Generated: 2016-09-06 20:09
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: JS

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `wkf_task`.`ticket` 
DROP FOREIGN KEY `fk_ticket_refResolution1`;

ALTER TABLE `wkf_task`.`iteration` 
ADD COLUMN `start_date` DATE NOT NULL AFTER `id`,
ADD COLUMN `status_id` INT(11) NOT NULL AFTER `project_id`,
ADD COLUMN `number` INT(11) NOT NULL AFTER `status_id`,
ADD INDEX `fk_iteration_status1_idx` (`status_id` ASC);

ALTER TABLE `wkf_task`.`sub_ticket` 
ADD COLUMN `owner_user_id` INT(11) NOT NULL AFTER `ticket_id`,
ADD INDEX `fk_sub_ticket_user1_idx` (`owner_user_id` ASC);

ALTER TABLE `wkf_task`.`ticket` 
CHANGE COLUMN `resolution_id` `resolution_id` INT(11) NULL DEFAULT NULL ,
ADD COLUMN `story_points` INT(11) NULL DEFAULT NULL AFTER `worked_time`;

ALTER TABLE `wkf_task`.`iteration` 
ADD CONSTRAINT `fk_iteration_status1`
  FOREIGN KEY (`status_id`)
  REFERENCES `wkf_task`.`status` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `wkf_task`.`sub_ticket` 
ADD CONSTRAINT `fk_sub_ticket_user1`
  FOREIGN KEY (`owner_user_id`)
  REFERENCES `wkf_task`.`user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `wkf_task`.`ticket` 
DROP FOREIGN KEY `fk_ticket_iteration1`;

ALTER TABLE `wkf_task`.`ticket` ADD CONSTRAINT `fk_ticket_refResolution1`
  FOREIGN KEY (`resolution_id`)
  REFERENCES `wkf_task`.`resolution` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_ticket_iteration1`
  FOREIGN KEY (`iteration_id`)
  REFERENCES `wkf_task`.`iteration` (`id`)
  ON DELETE SET NULL
  ON UPDATE NO ACTION;


ALTER TABLE `wkf_task`.`iteration` 
DROP FOREIGN KEY `fk_iteration_project1`;

ALTER TABLE `wkf_task`.`iteration` 
ADD CONSTRAINT `fk_iteration_project1`
  FOREIGN KEY (`project_id`)
  REFERENCES `wkf_task`.`project` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

  
ALTER TABLE `wkf_task`.`ticket` 
DROP FOREIGN KEY `fk_ticket_project1`;

ALTER TABLE `wkf_task`.`ticket` ADD CONSTRAINT `fk_ticket_project1`
  FOREIGN KEY (`project_id`)
  REFERENCES `wkf_task`.`project` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
  

-- Data updates

-- -----------------------------------------------------
-- Data for table `resolution`
-- -----------------------------------------------------
START TRANSACTION;
TRUNCATE TABLE resolution;
INSERT INTO `resolution` (`id`, `name`) VALUES (1, 'Исправлен');
INSERT INTO `resolution` (`id`, `name`) VALUES (2, 'Не нужен');
INSERT INTO `resolution` (`id`, `name`) VALUES (3, 'Дубликат');

UPDATE ticket
SET resolution_id = null
WHERE resolution_id = 1;

UPDATE ticket
SET resolution_id = resolution_id - 1;

COMMIT;


-- -----------------------------------------------------
-- Data for table `ticket_type`
-- -----------------------------------------------------
START TRANSACTION;
TRUNCATE TABLE ticket_type;
INSERT INTO `ticket_type` (`id`, `name`) VALUES (1, 'Эпик');
INSERT INTO `ticket_type` (`id`, `name`) VALUES (2, 'История');
INSERT INTO `ticket_type` (`id`, `name`) VALUES (3, 'Задача');
INSERT INTO `ticket_type` (`id`, `name`) VALUES (4, 'Ошибка');

UPDATE ticket
SET ticket_type_id = ticket_type_id + 1;

COMMIT;

UPDATE  `status` SET  `name` =  'Блокирован' WHERE  `status`.`id` =3;
INSERT INTO `status` (`id`, `name`) VALUES
(8, 'Не выполнен'),
(9, 'Закрыт частично'),
(10, 'Выполнен');



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;