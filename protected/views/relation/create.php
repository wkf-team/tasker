<?php
/* @var $this RelationController */
/* @var $model Relation */

$this->breadcrumbs=array(
	'Ticket'=>array('ticket/view', 'id'=>($direction == "from" ? $model->ticket_from_id : $model->ticket_to_id)),
	'Create attachment',
);

$this->menu=array(
	array('label'=>'List Relation', 'url'=>array('index')),
	array('label'=>'Manage Relation', 'url'=>array('admin')),
);
?>

<h1>Create Relation</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'direction'=>$direction)); ?>