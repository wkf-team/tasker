<?php
/* @var $this TicketController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Задачи',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1>Список задач</h1>

<?php $this->widget('TW_TicketList', array(
	'dataProvider'=>$dataProvider,
	'noChildren'=>$noChildren,
	'allChildren'=>false,
)); ?>