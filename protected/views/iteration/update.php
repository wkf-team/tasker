<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Iterations'=>array('index'),
	$model->getLabel()=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Iteration', 'url'=>array('index')),
	array('label'=>'Create Iteration', 'url'=>array('create')),
	array('label'=>'View Iteration', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Iteration', 'url'=>array('admin')),
);
?>

<h1>Update Iteration <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>