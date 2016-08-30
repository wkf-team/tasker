<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Задачи'=>array('admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1>Создать задачу</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<script>
$(function () {
	$(".updateOnly").hide();
});
</script>