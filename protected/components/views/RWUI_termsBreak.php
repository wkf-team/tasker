<div id="termsBreak">
Нарушения сроков:<br/> 
    <?php 
    foreach($this->breaks as $break) {
        switch ($break->error_type) {
			case 1 :
			echo "Открытая задача в закрытой цели: " . CHtml::link($break->subject, array('ticket/view', 'id' => $break->ticket_id)) . "<br/>";
			break;
			case 2:
			echo "Просрочена: " . CHtml::link($break->subject, array('ticket/view', 'id' => $break->ticket_id)) . "<br/>";
			break;
			case 3:
			echo "Задача не уложится в срок: " . CHtml::link($break->subject, array('ticket/view', 'id' => $break->ticket_id)) . "<br/>";
			break;
			case 4:
			echo "Перегруз пользователя: " . CHtml::link($break->user_name, array('ticket/UsersTasks', 'id' => $break->user_id)) . ", нарушение срока " . CHtml::link($break->subject, array('ticket/view', 'id' => $break->ticket_id)) . "<br/>";
			break;
		}
    }
    ?>
</div>