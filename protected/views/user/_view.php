<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<?php echo CHtml::encode($data->id); ?>. 
	<b><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?></b> 
	(<?php echo CHtml::encode($data->usergroup->name); ?>)
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mail')); ?>:</b>
	<?php echo CHtml::encode($data->mail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('work_time_per_week')); ?>:</b>
	<?php echo CHtml::encode($data->work_time_per_week); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notification_enabled')); ?>:</b>
	<?php echo $data->notification_enabled == 1 ? "yes" : "no"; ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('digest_enabled')); ?>:</b>
	<?php echo $data->digest_enabled == 1 ? "yes" : "no"; ?>
	<br />


</div>