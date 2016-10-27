<?php
/* @var $this IterationController */
/* @var $model Iteration */

$this->breadcrumbs=array(
	'Итерации'=>array('index'),
	$model->getLabel(),
);

$this->menu=array(
	array('label'=>'Текущая', 'url'=>array('index')),
	array('label'=>'Create Iteration', 'url'=>array('create')),
	array('label'=>'Update Iteration', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Iteration', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View <?php echo $model->getLabel(); ?></h1>

<?php
$this->renderPartial('_viewWithTickets', ['model'=>$model]);
?>