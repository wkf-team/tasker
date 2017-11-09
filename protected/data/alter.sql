CREATE 
     OR REPLACE 
VIEW `v_goals_complete` AS
     SELECT 
        `goals`.`id` AS `id`,
        `goals`.`subject` AS `subject`,
        `goals`.`project_id` AS `project_id`,
        COUNT(`tasks`.`id`) AS `total`,
        (SUM(IF((`tasks`.`status_id` >= 6), 1, 0)) + 
        SUM(IF((`tasks1`.`status_id` >= 6), 1, 0)) +
        SUM(IF((`tasks2`.`status_id` >= 6), 1, 0)) +
        SUM(IF((`tasks3`.`status_id` >= 6), 1, 0)) +
        SUM(IF((`tasks4`.`status_id` >= 6), 1, 0)) +
        SUM(IF((`tasks5`.`status_id` >= 6), 1, 0)) +
        SUM(IF((`tasks6`.`status_id` >= 6), 1, 0)) +
        SUM(IF((`tasks7`.`status_id` >= 6), 1, 0))
        )AS `closed`,
        `goals`.`due_date` AS `due_date`
    FROM
        (`ticket` `goals`
        LEFT JOIN `ticket` `tasks` ON ((`tasks`.`parent_ticket_id` = `goals`.`id`))
        LEFT JOIN `ticket` `tasks1` ON ((`tasks1`.`parent_ticket_id` = `tasks`.`id`))
        LEFT JOIN `ticket` `tasks2` ON ((`tasks2`.`parent_ticket_id` = `tasks1`.`id`))
        LEFT JOIN `ticket` `tasks3` ON ((`tasks3`.`parent_ticket_id` = `tasks2`.`id`))
        LEFT JOIN `ticket` `tasks4` ON ((`tasks4`.`parent_ticket_id` = `tasks3`.`id`))
        LEFT JOIN `ticket` `tasks5` ON ((`tasks5`.`parent_ticket_id` = `tasks4`.`id`))
        LEFT JOIN `ticket` `tasks6` ON ((`tasks6`.`parent_ticket_id` = `tasks5`.`id`))
        LEFT JOIN `ticket` `tasks7` ON ((`tasks7`.`parent_ticket_id` = `tasks6`.`id`))
        
        )
    WHERE
        (((`goals`.`status_id` < 6)
            OR ((`goals`.`status_id` = 6)
            AND (`goals`.`end_date` >= (NOW() - INTERVAL 14 DAY))))
            AND (`goals`.`ticket_type_id` = 1))
    GROUP BY `goals`.`id`
    ORDER BY IFNULL(`goals`.`due_date`, '3000-01-01');
