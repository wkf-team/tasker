-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `priority`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `priority` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `status` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `resolution`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `resolution` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ticket_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ticket_type` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `usergroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `level` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `is_active` BOOLEAN NOT NULL default 1,
  `mail` VARCHAR(255) NULL DEFAULT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `work_time_per_week` INT(11) NULL DEFAULT NULL,
  `usergroup_id` INT(11) NOT NULL,
  `notification_enabled` TINYINT(1) NOT NULL,
  `digest_enabled` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_usergroup1_idx` (`usergroup_id` ASC),
  CONSTRAINT `fk_user_usergroup1`
    FOREIGN KEY (`usergroup_id`)
    REFERENCES `usergroup` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `project`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `project` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `start_date` DATE NOT NULL,
  `is_active` TINYINT(1) NOT NULL,
  `current_version` VARCHAR(25) NULL DEFAULT NULL,
  `next_version` VARCHAR(25) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `iteration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iteration` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `start_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `project_id` INT(11) NOT NULL,
  `status_id` INT(11) NOT NULL,
  `number` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_iteration_project1_idx` (`project_id` ASC),
  INDEX `fk_iteration_status1_idx` (`status_id` ASC),
  CONSTRAINT `fk_iteration_project1`
    FOREIGN KEY (`project_id`)
    REFERENCES `project` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_iteration_status1`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ticket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ticket` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(255) NOT NULL,
  `description` VARCHAR(10000) NULL DEFAULT NULL,
  `create_date` DATE NOT NULL,
  `estimate_start_date` DATE NULL DEFAULT NULL,
  `due_date` DATE NULL DEFAULT NULL,
  `end_date` DATE NULL DEFAULT NULL,
  `estimate_time` FLOAT NULL DEFAULT NULL,
  `worked_time` FLOAT NULL DEFAULT NULL,
  `story_points` INT NULL,
  `priority_id` INT(11) NOT NULL,
  `status_id` INT(11) NOT NULL,
  `resolution_id` INT(11) NULL,
  `ticket_type_id` INT(11) NOT NULL,
  `author_user_id` INT(11) NOT NULL,
  `owner_user_id` INT(11) NOT NULL,
  `tester_user_id` INT(11) NULL DEFAULT NULL,
  `responsible_user_id` INT(11) NOT NULL,
  `parent_ticket_id` INT(11) NULL DEFAULT NULL,
  `iteration_id` INT(11) NULL DEFAULT NULL,
  `project_id` INT(11) NOT NULL,
  `initial_version` VARCHAR(25) NULL,
  `resolved_version` VARCHAR(25) NULL,
  `order_num` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ticket_refPriority_idx` (`priority_id` ASC),
  INDEX `fk_ticket_refStatus1_idx` (`status_id` ASC),
  INDEX `fk_ticket_refResolution1_idx` (`resolution_id` ASC),
  INDEX `fk_ticket_refTicketType1_idx` (`ticket_type_id` ASC),
  INDEX `fk_ticket_user1_idx` (`author_user_id` ASC),
  INDEX `fk_ticket_user2_idx` (`owner_user_id` ASC),
  INDEX `fk_ticket_ticket1_idx` (`parent_ticket_id` ASC),
  INDEX `fk_ticket_user3_idx` (`tester_user_id` ASC),
  INDEX `fk_ticket_user4_idx` (`responsible_user_id` ASC),
  INDEX `fk_ticket_iteration1_idx` (`iteration_id` ASC),
  INDEX `fk_ticket_project1_idx` (`project_id` ASC),
  CONSTRAINT `fk_ticket_refPriority`
    FOREIGN KEY (`priority_id`)
    REFERENCES `priority` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refStatus1`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refResolution1`
    FOREIGN KEY (`resolution_id`)
    REFERENCES `resolution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refTicketType1`
    FOREIGN KEY (`ticket_type_id`)
    REFERENCES `ticket_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user1`
    FOREIGN KEY (`author_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user2`
    FOREIGN KEY (`owner_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_ticket1`
    FOREIGN KEY (`parent_ticket_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user3`
    FOREIGN KEY (`tester_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user4`
    FOREIGN KEY (`responsible_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_iteration1`
    FOREIGN KEY (`iteration_id`)
    REFERENCES `iteration` (`id`)
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_project1`
    FOREIGN KEY (`project_id`)
    REFERENCES `project` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `attachment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `attachment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `create_date` DATE NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `ticket_id` INT(11) NOT NULL,
  `author_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_attachment_ticket1_idx` (`ticket_id` ASC),
  INDEX `fk_attachment_user1_idx` (`author_id` ASC),
  CONSTRAINT `fk_attachment_ticket1`
    FOREIGN KEY (`ticket_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_attachment_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `comment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `create_date` DATETIME NOT NULL,
  `text` VARCHAR(1000) NULL DEFAULT NULL,
  `ticket_id` INT(11) NOT NULL,
  `author_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comment_ticket1_idx` (`ticket_id` ASC),
  INDEX `fk_comment_user1_idx` (`author_id` ASC),
  CONSTRAINT `fk_comment_ticket1`
    FOREIGN KEY (`ticket_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_user1`
    FOREIGN KEY (`author_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `relation_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `relation_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `direct_name` VARCHAR(45) NOT NULL,
  `reverse_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `relation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `relation` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ticket_from_id` INT(11) NOT NULL,
  `ticket_to_id` INT(11) NOT NULL,
  `relation_type_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_relation_ticket1_idx` (`ticket_from_id` ASC),
  INDEX `fk_relation_ticket2_idx` (`ticket_to_id` ASC),
  INDEX `fk_relation_relation_type1_idx` (`relation_type_id` ASC),
  CONSTRAINT `fk_relation_ticket1`
    FOREIGN KEY (`ticket_from_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relation_ticket2`
    FOREIGN KEY (`ticket_to_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_relation_relation_type1`
    FOREIGN KEY (`relation_type_id`)
    REFERENCES `relation_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `spent_time`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spent_time` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `create_date` DATE NOT NULL,
  `hours_count` INT(11) NOT NULL,
  `ticket_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_spent_time_ticket1_idx` (`ticket_id` ASC),
  INDEX `fk_spent_time_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_spent_time_ticket1`
    FOREIGN KEY (`ticket_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_spent_time_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sub_ticket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sub_ticket` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `text` VARCHAR(255) NOT NULL,
  `order_num` INT(11) NOT NULL,
  `is_done` TINYINT(1) NOT NULL,
  `iteration_id` INT(11) NULL DEFAULT NULL,
  `ticket_id` INT(11) NOT NULL,
  `owner_user_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_sub_ticket_iteration1_idx` (`iteration_id` ASC),
  INDEX `fk_sub_ticket_ticket1_idx` (`ticket_id` ASC),
  INDEX `fk_sub_ticket_user1_idx` (`owner_user_id` ASC),
  CONSTRAINT `fk_sub_ticket_iteration1`
    FOREIGN KEY (`iteration_id`)
    REFERENCES `iteration` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sub_ticket_ticket1`
    FOREIGN KEY (`ticket_id`)
    REFERENCES `ticket` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sub_ticket_user1`
    FOREIGN KEY (`owner_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ticket_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ticket_history` (
  `hist_id` INT(11) NOT NULL AUTO_INCREMENT,
  `hist_create_date` DATE NOT NULL,
  `hist_create_user_id` INT(11) NOT NULL,
  `hist_reason` VARCHAR(1024) NOT NULL,
  `id` INT(11) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `description` VARCHAR(10000) NULL DEFAULT NULL,
  `create_date` DATE NOT NULL,
  `estimate_start_date` DATE NULL DEFAULT NULL,
  `due_date` DATE NULL DEFAULT NULL,
  `end_date` DATE NULL DEFAULT NULL,
  `estimate_time` INT(11) NULL DEFAULT NULL,
  `worked_time` INT(11) NULL DEFAULT NULL,
  `priority_id` INT(11) NOT NULL,
  `status_id` INT(11) NOT NULL,
  `resolution_id` INT(11) NOT NULL,
  `ticket_type_id` INT(11) NOT NULL,
  `author_user_id` INT(11) NOT NULL,
  `owner_user_id` INT(11) NOT NULL,
  `tester_user_id` INT(11) NULL DEFAULT NULL,
  `responsible_user_id` INT(11) NOT NULL,
  `parent_ticket_id` INT(11) NULL DEFAULT NULL,
  `iteration_id` INT(11) NOT NULL,
  `project_id` INT(11) NOT NULL,
  PRIMARY KEY (`hist_id`),
  INDEX `fk_ticket_refPriority_idx` (`priority_id` ASC),
  INDEX `fk_ticket_refStatus1_idx` (`status_id` ASC),
  INDEX `fk_ticket_refResolution1_idx` (`resolution_id` ASC),
  INDEX `fk_ticket_refTicketType1_idx` (`ticket_type_id` ASC),
  INDEX `fk_ticket_user1_idx` (`author_user_id` ASC),
  INDEX `fk_ticket_user2_idx` (`owner_user_id` ASC),
  INDEX `fk_ticket_user3_idx` (`tester_user_id` ASC),
  INDEX `fk_ticket_user4_idx` (`responsible_user_id` ASC),
  INDEX `fk_ticket_iteration1_idx` (`iteration_id` ASC),
  INDEX `fk_ticket_project1_idx` (`project_id` ASC),
  INDEX `fk_ticket_history_user1_idx` (`hist_create_user_id` ASC),
  CONSTRAINT `fk_ticket_refPriority0`
    FOREIGN KEY (`priority_id`)
    REFERENCES `priority` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refStatus10`
    FOREIGN KEY (`status_id`)
    REFERENCES `status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refResolution10`
    FOREIGN KEY (`resolution_id`)
    REFERENCES `resolution` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_refTicketType10`
    FOREIGN KEY (`ticket_type_id`)
    REFERENCES `ticket_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user10`
    FOREIGN KEY (`author_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user20`
    FOREIGN KEY (`owner_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user30`
    FOREIGN KEY (`tester_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user40`
    FOREIGN KEY (`responsible_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_iteration10`
    FOREIGN KEY (`iteration_id`)
    REFERENCES `iteration` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_project10`
    FOREIGN KEY (`project_id`)
    REFERENCES `project` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_history_user1`
    FOREIGN KEY (`hist_create_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_has_project`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_has_project` (
  `user_id` INT(11) NOT NULL,
  `project_id` INT(11) NOT NULL,
  `get_notifications` TINYINT(1) NOT NULL,
  `is_selected` TINYINT(1) NOT NULL,
  PRIMARY KEY (`user_id`, `project_id`),
  INDEX `fk_user_has_project_project1_idx` (`project_id` ASC),
  INDEX `fk_user_has_project_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_project_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_project_project1`
    FOREIGN KEY (`project_id`)
    REFERENCES `project` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Placeholder table for view `v_goals_complete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `v_goals_complete` (`id` INT, `subject` INT, `total` INT, `closed` INT, `due_date` INT);

-- -----------------------------------------------------
-- Placeholder table for view `v_terms_break`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `v_terms_break` (`error_type` INT, `ticket_id` INT, `subject` INT, `due_date` INT, `calc_date` INT, `user_id` INT, `user_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `v_users_balance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `v_users_balance` (`owner_user_id` INT, `user_name` INT, `total` INT, `sum_time` INT);

-- -----------------------------------------------------
-- View `v_goals_complete`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `v_goals_complete`;

CREATE 
     OR REPLACE 
VIEW `v_goals_complete` AS
     SELECT 
        `goals`.`id` AS `id`,
        `goals`.`subject` AS `subject`,
        `goals`.`project_id` AS `project_id`,
        (COUNT(DISTINCT `tasks`.`id`)
		) AS `total`,
        (SUM(IF((`tasks`.`status_id` >= 6), 1, 0))
        )AS `closed`,
        `goals`.`due_date` AS `due_date`
    FROM
        (`ticket` `goals`
        LEFT JOIN `ticket` `tasks` ON ((`tasks`.`parent_ticket_id` = `goals`.`id`))
        
        )
    WHERE
        (((`goals`.`status_id` < 6)
            OR ((`goals`.`status_id` = 6)
            AND (`goals`.`end_date` >= (NOW() - INTERVAL 14 DAY))))
            AND (`goals`.`ticket_type_id` = 1))
    GROUP BY `goals`.`id`
    ORDER BY IFNULL(`goals`.`due_date`, '3000-01-01');


-- -----------------------------------------------------
-- View `v_terms_break`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `v_terms_break`;

CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `v_terms_break` AS select 1 AS `error_type`,`tasks`.`id` AS `ticket_id`,`tasks`.`subject` AS `subject`,NULL AS `due_date`,NULL AS `calc_date`,NULL AS `user_id`,NULL AS `user_name` from (`ticket` `goals` left join `ticket` `tasks` on((`tasks`.`parent_ticket_id` = `goals`.`id`))) where ((`goals`.`ticket_type_id` = 1) and (`goals`.`status_id` >= 6) and (`tasks`.`status_id` < 6)) union select 2 AS `error_type`,`tasks`.`id` AS `id`,`tasks`.`subject` AS `subject`,`tasks`.`due_date` AS `due_date`,NULL AS `NULL`,`tasks`.`owner_user_id` AS `owner_user_id`,`user`.`name` AS `user_name` from (`ticket` `tasks` left join `user` on((`tasks`.`owner_user_id` = `user`.`id`))) where ((`tasks`.`status_id` < 6) and (`tasks`.`due_date` < curdate())) union select 3 AS `error_type`,`tasks`.`id` AS `id`,`tasks`.`subject` AS `subject`,`tasks`.`due_date` AS `due_date`,(curdate() + ((`tasks`.`estimate_time` / `owner`.`work_time_per_week`) * 7)) AS `calc_date`,`tasks`.`owner_user_id` AS `owner_user_id`,`owner`.`name` AS `user_name` from ((`ticket` `goals` left join `ticket` `tasks` on((`tasks`.`parent_ticket_id` = `goals`.`id`))) left join `user` `owner` on((`tasks`.`owner_user_id` = `owner`.`id`))) where ((`goals`.`ticket_type_id` = 1) and (`goals`.`status_id` < 6) and (`tasks`.`status_id` < 6) and (`owner`.`work_time_per_week` > 0) and (`tasks`.`due_date` is not null) and ((curdate() + ((`tasks`.`estimate_time` / `owner`.`work_time_per_week`) * 7)) > `tasks`.`due_date`)) union select 4 AS `error_type`,`goals`.`id` AS `id`,`goals`.`subject` AS `subject`,`goals`.`due_date` AS `due_date`,(curdate() + ((sum(`tasks`.`estimate_time`) / `owner`.`work_time_per_week`) * 7)) AS `calc_date`,`owner`.`id` AS `user_id`,`owner`.`name` AS `user_name` from ((`ticket` `goals` left join `ticket` `tasks` on((`tasks`.`parent_ticket_id` = `goals`.`id`))) left join `user` `owner` on((`tasks`.`owner_user_id` = `owner`.`id`))) where ((`goals`.`ticket_type_id` = 1) and (`goals`.`status_id` < 6) and (`tasks`.`status_id` < 6) and (`owner`.`work_time_per_week` > 0) and (`goals`.`due_date` is not null)) group by `owner`.`id`,`goals`.`id`,`owner`.`work_time_per_week`,`goals`.`due_date` having ((curdate() + ((sum(`tasks`.`estimate_time`) / `owner`.`work_time_per_week`) * 7)) > `goals`.`due_date`);

-- -----------------------------------------------------
-- View `v_users_balance`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `v_users_balance`;

CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `v_users_balance` AS select `t`.`owner_user_id` AS `owner_user_id`,`user`.`name` AS `user_name`,count(`t`.`id`) AS `total`,sum(`t`.`estimate_time`) AS `sum_time` from (`ticket` `t` left join `user` on((`user`.`id` = `t`.`owner_user_id`))) where ((`t`.`status_id` < 6) and (`t`.`ticket_type_id` > 1)) group by `t`.`owner_user_id`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `priority`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `priority` (`id`, `name`) VALUES (1, 'Низкий');
INSERT INTO `priority` (`id`, `name`) VALUES (2, 'Средний');
INSERT INTO `priority` (`id`, `name`) VALUES (3, 'Высокий');
INSERT INTO `priority` (`id`, `name`) VALUES (4, 'Блокер');

COMMIT;


-- -----------------------------------------------------
-- Data for table `status`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `status` (`id`, `name`) VALUES (1, 'Открыт');
INSERT INTO `status` (`id`, `name`) VALUES (2, 'Переоткрыт');
INSERT INTO `status` (`id`, `name`) VALUES (3, 'Блокирован');
INSERT INTO `status` (`id`, `name`) VALUES (4, 'В работе');
INSERT INTO `status` (`id`, `name`) VALUES (5, 'На тестировании');
INSERT INTO `status` (`id`, `name`) VALUES (6, 'Решен');
INSERT INTO `status` (`id`, `name`) VALUES (7, 'Закрыт');
INSERT INTO `status` (`id`, `name`) VALUES (8, 'Не выполнен');
INSERT INTO `status` (`id`, `name`) VALUES (9, 'Закрыт частично');
INSERT INTO `status` (`id`, `name`) VALUES (10, 'Выполнен');

COMMIT;


-- -----------------------------------------------------
-- Data for table `resolution`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `resolution` (`id`, `name`) VALUES (1, 'Исправлен');
INSERT INTO `resolution` (`id`, `name`) VALUES (2, 'Не нужен');
INSERT INTO `resolution` (`id`, `name`) VALUES (3, 'Дубликат');

COMMIT;


-- -----------------------------------------------------
-- Data for table `ticket_type`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `ticket_type` (`id`, `name`) VALUES (1, 'Эпик');
INSERT INTO `ticket_type` (`id`, `name`) VALUES (2, 'История');
INSERT INTO `ticket_type` (`id`, `name`) VALUES (3, 'Задача');
INSERT INTO `ticket_type` (`id`, `name`) VALUES (4, 'Ошибка');

COMMIT;


-- -----------------------------------------------------
-- Data for table `usergroup`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `usergroup` (`id`, `name`, `level`) VALUES (1, 'Общая', 0);
INSERT INTO `usergroup` (`id`, `name`, `level`) VALUES (2, 'Участник', 10);
INSERT INTO `usergroup` (`id`, `name`, `level`) VALUES (3, 'Координатор', 20);
INSERT INTO `usergroup` (`id`, `name`, `level`) VALUES (4, 'Администратор', 30);

COMMIT;


-- -----------------------------------------------------
-- Data for table `user`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `user` (`id`, `name`, `mail`, `password`, `work_time_per_week`, `usergroup_id`, `notification_enabled`, `digest_enabled`) VALUES (1, 'admin', NULL, '$1$4175$ZEZh1cGO4IxtOEdt/kuXc/', NULL, 4, 0, 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `relation_type`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `relation_type` (`id`, `direct_name`, `reverse_name`) VALUES (1, 'Блокирует', 'Блокирована');
INSERT INTO `relation_type` (`id`, `direct_name`, `reverse_name`) VALUES (2, 'Дублирует', 'Дублирована');

COMMIT;

