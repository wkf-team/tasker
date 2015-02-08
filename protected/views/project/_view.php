<?php
/* @var $this ProjectController */
/* @var $data Project */
?>

<div class="view">
	
	<?php echo CHtml::encode($data->id); ?>. 
	<b><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?></b> 
	(
	<?php
	if ($data->IsSelected()) echo "<b>Выбран</b>";
	else echo CHtml::ajaxLink("Выбрать", array('SetSelected', 'id'=>$data->id), array('success'=>'function() {window.location.reload();}')); ?>
	)
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo $data->encodeDate($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo $data->is_active == 1 ? "yes" : "no"; ?>
	<br />


</div>