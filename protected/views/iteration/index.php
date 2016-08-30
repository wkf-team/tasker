<?php
/* @var $this IterationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Бэклог',
);

$this->menu=array(
	array('label'=>'Create Iteration', 'url'=>array('create')),
);
?>

<h1>Текущая итерация</h1>
<?php
if ($model) {
	echo $model->getLabel();
	$this->renderPartial('_viewWithTickets', ['model'=>$model]);
}
?>
<h1>Бэклог</h1>
<?php
$this->widget('TW_TicketHList', [
	'filterForBacklog'=>true,
	'showFooterButtons'=>true,
	'iteration_id'=>$model->id,
]);
?>