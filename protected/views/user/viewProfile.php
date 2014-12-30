<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	$model->name,
);

$this->menu=array(
	array('label'=>'Изменить', 'url'=>array('updateProfile'))
);
?>

<h1>Профиль пользователя <?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => 'css/grid.css',
	'attributes'=>array(
		'id',
		'name',
		'mail',
		'work_time_per_week',
		'usergroup.name:text:'.CHtml::activeLabel($model, "usergroup_id"),
		'notification_enabled:boolean',
		'digest_enabled:boolean',
	),
)); ?>
