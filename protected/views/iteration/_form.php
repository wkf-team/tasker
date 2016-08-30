<?php
/* @var $this IterationController */
/* @var $model Iteration */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'iteration-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'start_date'); ?>
		<?php echo $form->dateField($model,'start_date'); ?>
		<?php echo $form->error($model,'start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php echo $form->dateField($model,'due_date'); ?>
		<?php echo $form->error($model,'due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'project_id'); ?>
		<?php echo $form->dropDownList($model,'project_id', CHTML::listData(Project::model()->findAll(array(
				'condition'=>'u.user_id = :uid',
				'join'=>'INNER JOIN user_has_project AS u ON u.project_id = t.id',
				'params'=>array(':uid'=>Yii::app()->user->id),
			)), 'id', 'name')); ?>
		<?php echo $form->error($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status_id'); ?>
		<?php echo $form->dropDownList($model,'status_id', CHTML::listData(
			Status::model()->findAll('id IN (1,4,8,9,10)'), 'id', 'name'
		)); ?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->