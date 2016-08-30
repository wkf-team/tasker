<?php
/* @var $this IterationController */
/* @var $data Iteration */
?>

<div class="view">

	<?php echo CHtml::link(CHtml::encode($data->getLabel()), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_id')); ?>:</b>
	<span><?php echo CHtml::encode($data->status->name); ?></span>
	<br />


</div>