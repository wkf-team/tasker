Проект: <?php echo $projectName; ?>
<?php if (count($ticketsClosed) > 0) { ?>
За прошедшую неделю были выполнены задачи:
<ul>
<?php
	for ($i = 0; $i < count($ticketsClosed); $i++) {
		echo "<li>".CHtml::link($ticketsClosed[$i]->subject, Sendmail::$baseUrl.strstr(CHtml::normalizeUrl(array('ticket/view', 'id'=>$ticketsClosed[$i]->id)),'?'))."</li>";
	}
?>
</ul><br/>
<?php } else { ?>
За прошедшую неделю ни одной задачи закрыто не было.<br/>
<?php } ?>

<?php if (count($ticketsOverdue) > 0) { ?>
В данный момент просрочены задачи:
<ul>
<?php
	for ($i = 0; $i < count($ticketsOverdue); $i++) {
		echo "<li>".CHtml::link($ticketsOverdue[$i]->subject, Sendmail::$baseUrl.strstr(CHtml::normalizeUrl(array('ticket/view', 'id'=>$ticketsOverdue[$i]->id)),'?'))." (".$ticketsOverdue[$i]->ownerUser->name.")</li>";
	}
?>
</ul><br/>
<?php } else { ?>
Просроченных задач нет.<br/>
<?php }?>