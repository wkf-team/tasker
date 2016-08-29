<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Iterations'=>array('index'),
	$model->getLabel(),
);

$this->menu=array(
	array('label'=>'List Iteration', 'url'=>array('index')),
	array('label'=>'Create Iteration', 'url'=>array('create')),
	array('label'=>'Update Iteration', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Iteration', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Iteration', 'url'=>array('admin')),
);
?>

<h1>View <?php echo $model->getLabel(); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'start_date',
		'due_date',
		'project.name:text:'.CHtml::activeLabel($model, "project_id"),
		'status.name:text:'.CHtml::activeLabel($model, "status_id"),
		'number',
	),
)); ?>
