CREATE VIEW `v_goals_complete` AS
    select 
        `goals`.`id` AS `id`,
        `goals`.`subject` AS `subject`,
        count(`tasks`.`id`) AS `total`,
        sum(if((`tasks`.`status_id` >= 3), 1, 0)) AS `closed`
    from
        (`ticket` `goals`
        left join `ticket` `tasks` ON ((`tasks`.`parent_ticket_id` = `goals`.`id`)))
    where
        ((`goals`.`status_id` < 3)
            and (`goals`.`ticket_type_id` = 1))
    group by `goals`.`id`;

CREATE VIEW `v_users_balance` AS
    select 
        `t`.`owner_user_id` AS `owner_user_id`,
        `user`.`name` AS `user_name`,
        count(`t`.`id`) AS `total`,
        sum(`t`.`estimate_time`) AS `sum_time`
    from
        (`ticket` `t`
        left join `user` ON ((`user`.`id` = `t`.`owner_user_id`)))
    where
        ((`t`.`status_id` < 3)
            and (`t`.`ticket_type_id` > 1))
    group by `t`.`owner_user_id`;

CREATE VIEW `v_terms_break` AS
    select 
        1 AS `error_type`,
        `tasks`.`id` AS `ticket_id`,
        `tasks`.`subject` AS `subject`,
        NULL AS `due_date`,
        NULL AS `calc_date`,
        NULL AS `user_id`,
        NULL AS `user_name`
    from
        (`ticket` `goals`
        left join `ticket` `tasks` ON ((`tasks`.`parent_ticket_id` = `goals`.`id`)))
    where
        ((`goals`.`ticket_type_id` = 1)
            and (`goals`.`status_id` >= 3)
            and (`tasks`.`status_id` < 3)) 
    union select 
        2 AS `error_type`,
        `tasks`.`id` AS `id`,
        `tasks`.`subject` AS `subject`,
        `tasks`.`due_date` AS `due_date`,
        NULL AS `NULL`,
        `tasks`.`owner_user_id` AS `owner_user_id`,
        `user`.`name` AS `user_name`
    from
        `ticket` `tasks` 
		left join `user` on `tasks`.`owner_user_id` = `user`.`id`
    where
        ((`tasks`.`status_id` < 3)
            and (`tasks`.`due_date` < curdate())) 
    union select 
        3 AS `error_type`,
        `tasks`.`id` AS `id`,
        `tasks`.`subject` AS `subject`,
        `tasks`.`due_date` AS `due_date`,
        (curdate() + ((`tasks`.`estimate_time` / `owner`.`work_time_per_week`) * 7)) AS `calc_date`,
        `tasks`.`owner_user_id` AS `owner_user_id`,
        `owner`.`name` AS `user_name`
    from
        ((`ticket` `goals`
        left join `ticket` `tasks` ON ((`tasks`.`parent_ticket_id` = `goals`.`id`)))
        left join `user` `owner` ON ((`tasks`.`owner_user_id` = `owner`.`id`)))
    where
        ((`goals`.`ticket_type_id` = 1)
            and (`goals`.`status_id` < 3)
            and (`tasks`.`status_id` < 3)
            and (`owner`.`work_time_per_week` > 0)
            and (`tasks`.`due_date` is not null)
            and ((curdate() + ((`tasks`.`estimate_time` / `owner`.`work_time_per_week`) * 7)) > `tasks`.`due_date`)) 
    union select 
        4 AS `error_type`,
        `goals`.`id` AS `id`,
        `goals`.`subject` AS `subject`,
        `goals`.`due_date` AS `due_date`,
        (curdate() + ((sum(`tasks`.`estimate_time`) / `owner`.`work_time_per_week`) * 7)) AS `calc_date`,
        `owner`.`id` AS `user_id`,
        `owner`.`name` AS `user_name`
    from
        ((`ticket` `goals`
        left join `ticket` `tasks` ON ((`tasks`.`parent_ticket_id` = `goals`.`id`)))
        left join `user` `owner` ON ((`tasks`.`owner_user_id` = `owner`.`id`)))
    where
        ((`goals`.`ticket_type_id` = 1)
            and (`goals`.`status_id` < 3)
            and (`tasks`.`status_id` < 3)
            and (`owner`.`work_time_per_week` > 0)
            and (`goals`.`due_date` is not null))
    group by `owner`.`id` , `goals`.`id` , `owner`.`work_time_per_week` , `goals`.`due_date`
    having ((curdate() + ((sum(`tasks`.`estimate_time`) / `owner`.`work_time_per_week`) * 7)) > `goals`.`due_date`);