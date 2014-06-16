<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	$model->name=>array('view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Просмотреть', 'url'=>array('view', 'id'=>$model->id))
);
?>

<h1>Изменить профиль</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>