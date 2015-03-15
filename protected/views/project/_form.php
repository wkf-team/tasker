<?php
/* @var $this ProjectController */
/* @var $model Project */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля со <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_date'); ?>
		<?php echo $form->dateField($model,'start_date'); ?>
		<?php echo $form->error($model,'start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_active'); ?>
		<?php echo $form->checkBox($model,'is_active'); ?>
		<?php echo $form->error($model,'is_active'); ?>
	</div>
	<?php if (!$model->isNewRecord) { ?>
		<h2>Права доступа</h2>
		<?php 
		$rights = new UserHasProject();
		$rights->project_id = $model->id;
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'rights-grid',
			'cssFile' => 'css/gridView.css',
			'dataProvider'=>$rights->search(),
			'filter'=>$rights,
			'filterPosition'=>'',
			'columns'=>array(
				array(
					'class'=>'CButtonColumn',
					'buttons'=>array(
						'view'=>array(
							'visible'=>'false',
						),
						'update'=>array(
							'url'=>'CHtml::normalizeUrl(array(\'project/switchRight\', \'id\'=>'.$model->id.', \'user_id\'=>$data->user_id))',
						),
						'delete'=>array(
							'url'=>'CHtml::normalizeUrl(array(\'project/removeRight\', \'id\'=>'.$model->id.', \'user_id\'=>$data->user_id))',
						),
					),
				),
				array(
					'name' => 'user_id',
					'value' => '$data->user->name',
					'type' => 'text'
				),
				array(
					'name' => 'get_notifications',
					'value' => '$data->get_notifications',
					'type' => 'boolean'
				),
			),
		)); ?>
	<button id="addUserHasProject">Добавить</button>
	<?php } // is new record ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$right = new UserHasProject();
$right->project_id = $model->id;
$this->renderPartial('rightForm', array('model'=>$right));
?>