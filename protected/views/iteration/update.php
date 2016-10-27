<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Итерации'=>array('index'),
	$model->getLabel()=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Текущая', 'url'=>array('index')),
	array('label'=>'Create Iteration', 'url'=>array('create')),
	array('label'=>'View Iteration', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Iteration <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>