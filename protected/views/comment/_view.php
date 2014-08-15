<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="view" style="background:white; color:black;">

	<?php
	echo CHtml::encode($data->author->name) . ", " . $data->encodeDate($data->create_date);
	?>
	<br />
	<?php echo CHtml::encode($data->text); ?>
	<br />
	
	<!-- <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_id')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_id); ?>
	<br />-->

</div>