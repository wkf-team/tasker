-- TODO: generate structure changes
-- TODO: set on delete for iteration

SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE  `ticket` ADD  `story_points` INT NULL AFTER  `worked_time` ;

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

SET FOREIGN_KEY_CHECKS=1;