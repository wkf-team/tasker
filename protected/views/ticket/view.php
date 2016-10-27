<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Бэклог'=>array('ticket/plan'),
	$model->subject,
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Перенести', 'url'=>array('selectProject', 'id'=>$model->id)),
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
?>
<br />

<div id="tabs">
	<ul>
		<li id="link-tabs-0"><a href="#tabs-0">Детали</a></li>
		<li id="link-tabs-1"><a href="#tabs-1">Комментарии</a></li>
		<li id="link-tabs-2"><a href="#tabs-2">Файлы</a></li>
		<li id="link-tabs-3"><a href="#tabs-3">Связи</a></li>
		<li id="link-tabs-4"><a href="#tabs-4">Подзадачи</a></li>
	</ul>
	<div id="tabs-0">
	<?php
	
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
			'story_points',
			'authorUser.name:text:'.CHtml::activeLabel($model, "author_user_id"),
			'testerUser.name:text:'.CHtml::activeLabel($model, "tester_user_id"),
			'responsibleUser.name:text:'.CHtml::activeLabel($model, "responsible_user_id"),
			array(
				'label' => CHtml::activeLabel($model, "iteration_id"),
				'value' => $model->iteration ? $model->iteration->getLabel() : null
			),
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
			'initial_version',
			'resolved_version',
		),
	)); 
	?>
	</div>
	<div id="tabs-1">
	<?php $this->widget('CW_CommentList', array('ticket_id'=>$model->id)); ?>
	</div>
	<div id="tabs-2">
	<?php $this->widget('AW_AttList', array('ticket_id'=>$model->id)); ?>
	</div>
	<div id="tabs-3">
	<?php $this->widget('LW_LinksList', array('ticket_id'=>$model->id)); ?>
	</div>
	<div id="tabs-4">
	<?php $this->widget('TW_InnerTaskList', array('ticket_id'=>$model->id)); ?>
	</div>
</div>
<script language="javascript">
	function updateLink(num, hide) {
		var link = $("#link-tabs-"+num+" a");
		var summary = $("#tabs-"+num+" .summary");
		if (num == 4) {
			summary = $(".summary_custome");
		}
		var count = 0;
		if (summary.length == 1) count = new RegExp("of ([0-9]+)").exec(summary.html())[1];
		if (summary.length == 2) count = parseInt(new RegExp("of ([0-9]+)").exec($(summary[0]).html())[1]) + parseInt(new RegExp("of ([0-9]+)").exec($(summary[1]).html())[1]);
		if (count == 0 && hide) link.hide();
		link.html(link.html() + " (" + count + ")");
		if (num == 4) {
			summary.hide();
		}
	}

	$(function(){
		updateLink(1, false);
		updateLink(2, false);
		updateLink(3, false);
		updateLink(4, false);
		$( "#tabs" ).tabs();
	});
</script>
<style>
.ui-tabs {
	padding:0.1em!important;
}
#tabs-0 a {
	color:blue!important;
}
#tabs {
	background : white;
}
</style>