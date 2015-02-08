<?php
/* @var $this ProjectController */
/* @var $model Project */

$this->breadcrumbs=array(
	'Проекты'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1>Новый проект</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>