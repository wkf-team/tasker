ALTER TABLE `wkf_task`.`ticket` 
CHANGE COLUMN `estimate_time` `estimate_time` FLOAT NULL DEFAULT NULL ,
CHANGE COLUMN `worked_time` `worked_time` FLOAT NULL DEFAULT NULL ;
