<?php
/* @var $this AttachmentController */
/* @var $model Attachment */

$this->breadcrumbs=array(
	'Ticket'=>array('ticket/view', 'id'=>$model->ticket_id),
	'Create attachment',
);

$this->menu=array(
	array('label'=>'List Attachment', 'url'=>array('index')),
	array('label'=>'Manage Attachment', 'url'=>array('admin')),
);
?>

<h1>Create Attachment</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>