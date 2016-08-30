-- TODO: generate structure changes

SET FOREIGN_KEY_CHECKS=0;
-- -----------------------------------------------------
-- Data for table `wkf_task`.`resolution`
-- -----------------------------------------------------
START TRANSACTION;
TRUNCATE TABLE resolution;
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (1, 'Исправлен');
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (2, 'Не нужен');
INSERT INTO `wkf_task`.`resolution` (`id`, `name`) VALUES (3, 'Дубликат');

COMMIT;


-- -----------------------------------------------------
-- Data for table `wkf_task`.`ticket_type`
-- -----------------------------------------------------
START TRANSACTION;
TRUNCATE TABLE ticket_type;
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (1, 'Эпик');
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (2, 'История');
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (3, 'Задача');
INSERT INTO `wkf_task`.`ticket_type` (`id`, `name`) VALUES (4, 'Ошибка');

COMMIT;

INSERT INTO `status` (`id`, `name`) VALUES
(8, 'Не выполнен'),
(9, 'Закрыт частично'),
(10, 'Выполнен');

SET FOREIGN_KEY_CHECKS=1;