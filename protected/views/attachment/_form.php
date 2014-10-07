<?php
/* @var $this AttachmentController */
/* @var $model Attachment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'attachment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo CHtml::fileField('filename'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row buttons">
		<?php 
		echo CHtml::link("Отмена", array('ticket/view', 'id'=>$model->ticket_id), array('class'=>'action'));
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