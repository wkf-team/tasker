<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Задачи'=>array('index'),
	'Поиск',
);

$this->menu=array(
	array('label'=>'Список задач', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('ticket-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Поиск задач</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ticket-grid',
	'cssFile' => 'css/gridView.css',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'ticket_type_id',
			'value' => '$data->ticketType->name',
			'filter'=>CHTML::listData(TicketType::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		'subject',
		array(
			'name' => 'owner_user_id',
			'value' => '$data->ownerUser->name',
			'filter'=>CHTML::listData(User::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		'end_date',
		array(
			'name' => 'priority_id',
			'value' => '$data->priority->name',
			'filter'=>CHTML::listData(Priority::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		array(
			'name' => 'status_id',
			'value' => '$data->status->name',
			'filter'=>CHTML::listData(Status::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		array(
			'name' => 'resolution_id',
			'value' => '$data->resolution->name',
			'filter'=>CHTML::listData(Resolution::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		/*
		'id',
		'create_date',
		'due_date',
		'description',
		'estimate_time',
		'worked_time',
		'resolution_id',
		'author_user_id',
		'parent_ticket_id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
