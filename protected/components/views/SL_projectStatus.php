������: <?php echo $projectName; ?>
<?php if (count($ticketsClosed) > 0) { ?>
�� ��������� ������ ���� ��������� ������:
<ul>
<?php
	for ($i = 0; $i < count($ticketsClosed); $i++) {
		echo "<li>".CHtml::link($ticketsClosed[$i]->subject, Sendmail::$baseUrl.strstr(CHtml::normalizeUrl(array('ticket/view', 'id'=>$ticketsClosed[$i]->id)),'?'))."</li>";
	}
?>
</ul><br/>
<?php } else { ?>
�� ��������� ������ �� ����� ������ ������� �� ����.<br/>
<?php } ?>

<?php if (count($ticketsOverdue) > 0) { ?>
� ������ ������ ���������� ������:
<ul>
<?php
	for ($i = 0; $i < count($ticketsOverdue); $i++) {
		echo "<li>".CHtml::link($ticketsOverdue[$i]->subject, Sendmail::$baseUrl.strstr(CHtml::normalizeUrl(array('ticket/view', 'id'=>$ticketsOverdue[$i]->id)),'?'))." (".$ticketsOverdue[$i]->ownerUser->name.")</li>";
	}
?>
</ul><br/>
<?php } else { ?>
������������ ����� ���.<br/>
<?php }?>