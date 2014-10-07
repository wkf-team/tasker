<?php
/* @var $this RelationController */
/* @var $model Relation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'relation-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля с <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_from_id'); ?>
		<?php echo $form->textField($model,'ticket_from_id', array('readonly'=>($direction == "from" ? "readonly" : ""))); ?>
		<?php echo $form->error($model,'ticket_from_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'relation_type_id'); ?>
		<?php echo $form->dropDownList($model,'relation_type_id', CHTML::listData(RelationType::model()->findAll(), 'id', ($direction == "from" ? 'direct_name' : 'reverse_name'))); ?>
		<?php echo $form->error($model,'relation_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_to_id'); ?>
		<?php echo $form->textField($model,'ticket_to_id', array('readonly'=>($direction == "to" ? "readonly" : ""))); ?>
		<?php echo $form->error($model,'ticket_to_id'); ?>
	</div>

	<div class="row buttons">
		<?php 
		echo CHtml::hiddenField('direction', $direction);
		echo CHtml::link("Отмена", array('ticket/view', 'id'=>($direction == "from" ? $model->ticket_from_id : $model->ticket_to_id)), array('class'=>'action'));
		echo CHtml::submitButton('OK', array('class'=>'action', 'style'=>'margin:0px;')); 
		?>
	</div>

<?php $this->endWidget(); ?>
<script>
$(function () {
	$(".action").button();
});
</script>

</div><!-- form -->