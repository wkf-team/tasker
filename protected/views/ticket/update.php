<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Задачи'=>array('admin'),
	$model->subject=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Просмотреть', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1>Редактировать #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>