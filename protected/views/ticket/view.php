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

<?php
$actions = $model->getWorkflowActions();
foreach ($actions as $action) {
	echo CHtml::link($action['name'], array('ticket/makeWF', 'id'=>$model->id, 'action'=>$action['name']), array('class'=>'wf_action'.($action['needResolution']?' wf_resolution' : '')));
}
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


<div class="form" id="resolution" title="Задача решена">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticket-form',
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php
		echo $form->labelEx($model,'resolution_id');
		echo $form->dropDownList($model,'resolution_id', CHTML::listData(Resolution::model()->findAll('id > 1'), 'id', 'name'));
		echo $form->error($model,'resolution_id');
		echo $form->label($model,'worked_time');
		echo $form->textField($model, 'worked_time');
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<br/>
<h2>Вложения</h2>
<?php
echo CHtml::link("Add new attachment", array('attachment/create', 'ticket_id'=>$model->id), array('id'=>'btnAddAttachment'));
if ($attachmentProvider->totalItemCount > 0) {
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'attachment-grid',
	'cssFile' => 'css/gridView.css',
	'dataProvider'=>$attachmentProvider,
	'columns'=>array(
		array(
			'name' => 'name',
			'value' => 'CHtml::link($data->name, "attachments/".$data->name)',
			'type' => 'html'
		),
		array(
			'name' => 'author_id',
			'value' => '$data->author->name',
			'type' => 'text'
		),
		'create_date',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view' => array('visible'=>'false'),
				'update' => array('visible'=>'false'),
				'delete' => array('visible'=>'User::CheckLevel(20)'),//allow coordinator
			),
			'deleteButtonUrl'=>"CHtml::normalizeUrl(array('attachment/delete', 'id'=>\$data->id))",
		),
	),
));
}
?>

<?php
	if ($relationsListProvider->totalItemCount > 0) {
?>
<br/>
<h2>Связи</h2>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'cssFile' => 'css/gridView.css',
    'dataProvider'=>$relationsListProvider,
	'columns'=>array(
		array(
			'name' => 'ticket_from_id',
			'value' => '"#".$data->ticketFrom->id.". ".$data->ticketFrom->subject',
			//'filter'=>CHTML::listData(User::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		array(
			'name' => 'relation_type_id',
			'value' => '$data->relationType->direct_name',
			//'filter'=>CHTML::listData(User::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
		array(
			'name' => 'ticket_to_id',
			'value' => '"#".$data->ticketTo->id.". ".$data->ticketTo->subject',
			//'filter'=>CHTML::listData(User::model()->findAll(), 'id', 'name'),
			'type' => 'text'
		),
	),
));
}
?>

<?php
	if ($innerListProvider->totalItemCount > 0) {
?>
<br/>
<h2>Вложенные задачи</h2>
<table>
<tr>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('id'));?></th>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('ticket_type_id'));?></th>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('subject'));?></th>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('owner_user_id'));?></th>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('due_date'));?></th>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('priority_id'));?></th>
	<th><?php echo CHtml::encode($innerListProvider->model->getAttributeLabel('status_id'));?></th>
</tr>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$innerListProvider,
	'itemView'=>'_view',
)); ?>
</table>
<?php
}
?>

<br/>
<h2>Комментарии</h2>
<?php
echo CHtml::link("Add new comment", array('comment/create', 'ticket_id'=>$model->id), array('id'=>'btnAddComment'));
if ($commentsProvider->totalItemCount > 0) {
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$commentsProvider,
	'itemView'=>'/comment/_view',
));
}
?>

<script>
$(function () {
	$(".wf_action").button().click(function (ev, ui){
		var action = $(ev.target).closest(".wf_action");
		if (action.hasClass("wf_resolution")) {
			ev.preventDefault();
			//TODO make action
			$("#resolution").find("form").attr("action", action.attr("href"));
			$("#resolution").dialog('open');
		}
	});
	$("#btnAddAttachment").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    });
	$("#btnAddComment").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    });
	$("#resolution").dialog({
		autoOpen	: false,
		modal		: true,
		buttons		: {
			OK		: function () {
				var activeForm = $("#resolution").find("form");
				$.post(activeForm.get(0).action, activeForm.serialize(), function() {location.reload(); });
			},
			Cancel	: function () {$("#resolution").dialog("close");}
		}
	});
});
</script>
