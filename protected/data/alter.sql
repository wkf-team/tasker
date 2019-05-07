CREATE 
     OR REPLACE 
VIEW `v_goals_complete` AS
     SELECT 
        `goals`.`id` AS `id`,
        `goals`.`subject` AS `subject`,
        `goals`.`project_id` AS `project_id`,
        (COUNT(DISTINCT `tasks`.`id`)
		) AS `total`,
        (SUM(IF((`tasks`.`status_id` IN (4,5)), 1, 0))
        )AS `progress`,
        (SUM(IF((`tasks`.`status_id` >= 6), 1, 0))
        )AS `closed`,
        `goals`.`estimate_start_date` AS `start_date`,
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