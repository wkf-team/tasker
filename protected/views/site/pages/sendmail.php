<?php
if (isset($_GET['cmd'])) {
	switch($_GET['cmd']) {
		case 'status':
			Sendmail::mailWeeklyStatus();
			break;
		case 'overdue':
			Sendmail::mailOverdueTickets();
			break;
		case 'digest':
			Sendmail::mailDigestTickets();
			break;
	}
}
?>
<ul>
<li><?php echo CHtml::link('Project Status', array('site/page', 'view'=>'sendmail', 'cmd'=>'status')); ?></li>
<li><?php echo CHtml::link('Overdue', array('site/page', 'view'=>'sendmail', 'cmd'=>'overdue')); ?></li>
<li><?php echo CHtml::link('Digest', array('site/page', 'view'=>'sendmail', 'cmd'=>'digest')); ?></li>
</ul>