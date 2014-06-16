<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mail')); ?>:</b>
	<?php echo CHtml::encode($data->mail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('work_time_per_week')); ?>:</b>
	<?php echo CHtml::encode($data->work_time_per_week); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('usergroup_id')); ?>:</b>
	<?php echo CHtml::encode($data->usergroup->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notification_enabled')); ?>:</b>
	<?php echo $data->notification_enabled == 1 ? "yes" : "no"; ?>
	<br />


</div>