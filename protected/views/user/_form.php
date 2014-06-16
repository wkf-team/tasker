<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mail'); ?>
		<?php echo $form->emailField($model,'mail',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'mail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'work_time_per_week'); ?>
		<?php echo $form->textField($model,'work_time_per_week'); ?>
		<?php echo $form->error($model,'work_time_per_week'); ?>
	</div>

	<?php if(User::CheckLevel(30)) {?>
		<div class="row">
			<?php echo $form->labelEx($model,'usergroup_id'); ?>
			<?php echo $form->dropDownList($model,'usergroup_id', CHTML::listData(Usergroup::model()->findAll(), 'id', 'name')); ?>
			<?php echo $form->error($model,'usergroup_id'); ?>
		</div>
	<?php } ?>

	<div class="row">
		<?php echo $form->label($model,'notification_enabled'); ?>
		<?php echo $form->checkBox($model,'notification_enabled'); ?>
		<?php echo $form->error($model,'notification_enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->