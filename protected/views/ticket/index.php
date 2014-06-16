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

<table>
<tr>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('ticket_type_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('subject'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('owner_user_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('due_date'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('priority_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('status_id'));?></th>
</tr>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</table>