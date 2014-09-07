<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Задачи'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список задач', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Отложить на 3 дня', 'url'=>array('postpone', 'id'=>$model->id)),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить задачу?')),
	array('label'=>'Поиск', 'url'=>array('admin')),
);
?>

<h1> #<?php echo $model->ticketType->name . "-" . $model->id. ". ".$model->subject; ?></h1>
<style>
pre {
 white-space: pre-wrap;       /* css-3 */
 white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
 white-space: -pre-wrap;      /* Opera 4-6 */
 white-space: -o-pre-wrap;    /* Opera 7 */
 word-wrap: break-word;       /* Internet Explorer 5.5+ */
}
</style>

<?php $this->widget('WW_Workflow', array('model'=>$model)); ?>

<?php
echo "<br/><br/><b>Описание</b><br/>".$model->description;

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => 'css/grid.css',
	'attributes'=>array(
		'ownerUser.name:text:'.CHtml::activeLabel($model, "owner_user_id"),
		array(
			'label' => CHtml::activeLabel($model, "estimate_start_date"),
			'value' => $model->encodeDate($model->estimate_start_date)
		),
		array(
			'label' => CHtml::activeLabel($model, "due_date"),
			'value' => $model->encodeDate($model->due_date)
		),
		'priority.name:text:'.CHtml::activeLabel($model, "priority_id"),
		'status.name:text:'.CHtml::activeLabel($model, "status_id"),
		'resolution.name:text:'.CHtml::activeLabel($model, "resolution_id"),
		array(
			'label' => CHtml::activeLabel($model, "end_date"),
			'value' => $model->encodeDate($model->end_date)
		),
		'estimate_time',
		'worked_time',
		'authorUser.name:text:'.CHtml::activeLabel($model, "author_user_id"),
		'testerUser.name:text:'.CHtml::activeLabel($model, "tester_user_id"),
		'responsibleUser.name:text:'.CHtml::activeLabel($model, "responsible_user_id"),
		array(
			'label' => CHtml::activeLabel($model, "create_date"),
			'value' => $model->encodeDate($model->create_date)
		),
		array(
			'label' => CHtml::activeLabel($model, "parent_ticket_id"),
			'type' => 'html',
			'value' => 
				$model->parent_ticket_id ? 
					CHtml::link("#".$model->parent_ticket_id.". ".$model->parentTicket->subject, array('ticket/view', 'id'=>$model->parent_ticket_id)) 
					: null
		),
	),
)); ?>

<br />

<?php $this->widget('AW_AttList', array('ticket_id'=>$model->id, 'title'=>'Вложенные файлы')); ?>

<?php $this->widget('LW_LinksList', array('ticket_id'=>$model->id, 'title'=>'Ссылки')); ?>

<?php $this->widget('TW_InnerTaskList', array('ticket_id'=>$model->id, 'title'=>'Вложенные задачи')); ?>

<?php $this->widget('CW_CommentList', array('ticket_id'=>$model->id, 'title'=>'Комментарии')); ?>