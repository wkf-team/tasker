<?php
/* @var $this RelationController */
/* @var $data Relation */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_from_id')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_from_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_to_id')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_to_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('relation_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->relation_type_id); ?>
	<br />


</div>