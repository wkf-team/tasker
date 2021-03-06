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
		<?php echo $form->label($model,'is_active'); ?>
		<?php echo $form->checkBox($model,'is_active'); ?>
		<?php echo $form->error($model,'is_active'); ?>
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

	<div class="row">
		<?php echo $form->label($model,'digest_enabled'); ?>
		<?php echo $form->checkBox($model,'digest_enabled'); ?>
		<?php echo $form->error($model,'digest_enabled'); ?>
	</div>
	
	<?php if (!$model->isNewRecord) { ?>
		<h2>Оповещения</h2>
		<?php 
		$rights = new UserHasProject();
		$rights->user_id = $model->id;
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
							'url'=>'CHtml::normalizeUrl(array(\'user/'.(Yii::app()->user->id == $model->id ? 'SwitchMyRight' : 'SwitchUserRight').'\', \'id\'=>'.$model->id.', \'project_id\'=>$data->project_id))',
						),
						'delete'=>array(
							'visible'=>'false'
						),
					),
				),
				array(
					'name' => 'project_id',
					'value' => '$data->project->name',
					'type' => 'text'
				),
				array(
					'name' => 'get_notifications',
					'value' => '$data->get_notifications',
					'type' => 'boolean'
				),
			),
		));
	} // is new record
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
