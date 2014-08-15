<div id="usersBalance"> 
	Загрузка пользователей (задач / часов):<br/>
    <?php 
    foreach($this->users as $user) {
        echo CHtml::link($user->user_name, array('ticket/UsersTasks', 'id'=>$user->owner_user_id));
		echo ": " . $user->total . " / " . $user->sum_time . "<br/>";
    }
    ?>
</div>