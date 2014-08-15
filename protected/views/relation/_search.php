<?php
/* @var $this RelationController */
/* @var $model Relation */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_from_id'); ?>
		<?php echo $form->textField($model,'ticket_from_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_to_id'); ?>
		<?php echo $form->textField($model,'ticket_to_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'relation_type_id'); ?>
		<?php echo $form->textField($model,'relation_type_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->