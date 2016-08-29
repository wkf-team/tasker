<?php
/* @var $this IterationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Iterations',
);

$this->menu=array(
	array('label'=>'Create Iteration', 'url'=>array('create')),
	array('label'=>'Manage Iteration', 'url'=>array('admin')),
);
?>

<h1>Iterations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
