<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row buttons">
		<?php 
		echo CHtml::link("Отмена", array('ticket/view', 'id'=>$model->ticket_id), array('class'=>'action'));
		echo CHtml::submitButton('OK', array('class'=>'action'));
		?>
	</div>

<?php $this->endWidget(); ?>
<script>
$(function () {
	$(".action").button();
});
</script>
</div><!-- form -->