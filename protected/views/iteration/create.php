<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Итерации'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Текущая', 'url'=>array('index')),
);
?>

<h1>Create Iteration</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>