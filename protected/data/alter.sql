ALTER TABLE  `ticket` ADD  `order_num` INT NOT NULL ;

UPDATE ticket
SET order_num = id;