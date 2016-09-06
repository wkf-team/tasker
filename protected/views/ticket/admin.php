<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Поиск задач',
);

$this->menu=array(
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
$('.asearch-button').click(function(){
	$('.asearch-form').toggle();
	return false;
});
$('.asearch-form form').submit(function(){
	$.fn.yiiGridView.update('ticket-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Поиск задач</h1>

<?php echo CHtml::link('Поиск по полям','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<br/>
<?php echo CHtml::link('Специальный поиск','#',array('class'=>'asearch-button')); ?>
<div class="asearch-form" style="display:none; background:#eee;">
<?php $this->renderPartial('_asearch',array(
	'model'=>$model,
)); ?>
</div><!-- asearch-form -->

<br/><br/>
<p>
При необходимости можно использовать операторы сравнения (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
или <b>=</b>) в начале полей дат для уточнения логики поиска.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ticket-grid',
	'cssFile' => 'css/gridView.css',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name' => 'id',
			'value' => '$data->id',
			'type' => 'text',
			'htmlOptions'=>array('width'=>'20px')
		),
		array(
			'name' => 'project_id',
			'value' => '$data->project->name',
			'filter'=>CHTML::listData(Project::model()->findAll(array(
				'join' => 'INNER JOIN user_has_project AS p ON t.id = p.project_id',
				'condition' => 'p.user_id = :uid',
				'params' => array(':uid'=>Yii::app()->user->id)
				)), 'id', 'name'),
			'type' => 'text'
		),
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
		'due_date',
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
			'value' => '$data->resolution ? $data->resolution->name : null',
			'filter'=>CHTML::listData(Resolution::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
