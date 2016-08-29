<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Iterations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Iteration', 'url'=>array('index')),
	array('label'=>'Manage Iteration', 'url'=>array('admin')),
);
?>

<h1>Create Iteration</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>