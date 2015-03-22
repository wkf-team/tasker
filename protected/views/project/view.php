<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Проекты'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1>Проект №<?php echo $model->id.". ".$model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => 'css/grid.css',
	'attributes'=>array(
		'id',
		'name',
		array(
			'label' => CHtml::activeLabel($model, "start_date"),
			'value' => $model->encodeDate($model->start_date)
		),
		'is_active:boolean',
		'current_version',
		'next_version',
	),
)); ?>
