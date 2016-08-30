<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Бэклог'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Бэклог', 'url'=>array('index')),
);
?>

<h1>Create Iteration</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>