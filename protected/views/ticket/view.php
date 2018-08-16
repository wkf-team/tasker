<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Бэклог'=>array('ticket/plan'),
	$model->subject,
);

$this->pageTitle = $model->id. ". " . $model->subject." - ".Yii::app()->name;

$this->menu=array(
	array('label'=>'Перенести', 'url'=>array('selectProject', 'id'=>$model->id)),
	array('label'=>'Отложить на 3 дня', 'url'=>array('postpone', 'id'=>$model->id)),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить задачу?')),
);
?>

<h1> #<?php echo $model->id. ". ".$model->subject; ?></h1>
<style>
pre {
 white-space: pre-wrap;       /* css-3 */
 white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
 white-space: -pre-wrap;      /* Opera 4-6 */
 white-space: -o-pre-wrap;    /* Opera 7 */
 word-wrap: break-word;       /* Internet Explorer 5.5+ */
}
.ticket-details table {
    margin:5px;
    width: calc(100% - 10px);
}
.ticket-tabs {
    padding:5px;
    width: calc(100% - 10px);
}
</style>

<div>
    <?php $this->widget('WW_Workflow', array('model'=>$model)); ?> | 
    <?php echo CHtml::link("edit",['update', 'id'=>$model->id],['class'=>'wf_action',]); ?> |
    <?php echo CHtml::link("more...","",['class'=>'wf_action','onclick'=>'return ShowHideMenu();',]); ?>
</div>

<div>
    <div class="span-auto" style="width:70%;">
        <div class="span-auto">
            <br/>
            <h2>Детали</h2>
        </div>
        <div class="span-auto ticket-details" style="width:50%;">
            <?php
            $this->widget('zii.widgets.CDetailView', array(
                'data'=>$model,
                'cssFile' => 'css/grid.css',
                'attributes'=>array(
                    'ticketType.name:text:'.CHtml::activeLabel($model, "ticket_type_id"),
                    'priority.name:text:'.CHtml::activeLabel($model, "priority_id"),
                    'initial_version',
                    array(
                        'label' => CHtml::activeLabel($model, "parent_ticket_id"),
                        'type' => 'html',
                        'value' => 
                            $model->parent_ticket_id ? 
                                CHtml::link("#".$model->parent_ticket_id.". ".$model->parentTicket->subject, array('ticket/view', 'id'=>$model->parent_ticket_id)) 
                                : null
                    ),
                ),
            )); 
            ?>
        </div>
        <div class="span-side last ticket-details" style="width:50%;">
            <?php
            $this->widget('zii.widgets.CDetailView', array(
                'data'=>$model,
                'cssFile' => 'css/grid.css',
                'attributes'=>array(
                    'status.name:text:'.CHtml::activeLabel($model, "status_id"),
                    'resolution.name:text:'.CHtml::activeLabel($model, "resolution_id"),
                    'resolved_version',
                    array(
                        'label' => CHtml::activeLabel($model, "iteration_id"),
                        'value' => $model->iteration ? $model->iteration->getLabel() : null
                    ),
                ),
            )); 
            ?>
        </div>
        <div class="span-auto">
            <br/><br/><h2>Описание</h2><br/>
            <?php
            echo $model->description;
            ?>
        </div>
        <div class="span-auto ticket-tabs">
            <div id="tabs">
                <ul>
                    <li id="link-tabs-0"><a href="#tabs-0">Комментарии</a></li>
                    <li id="link-tabs-1"><a href="#tabs-1">Файлы</a></li>
                    <li id="link-tabs-2"><a href="#tabs-2">Связи</a></li>
                    <li id="link-tabs-3"><a href="#tabs-3">Подзадачи</a></li>
                </ul>
                <div id="tabs-0" style="color: black;">
                <?php $this->widget('CW_CommentList', array('ticket_id'=>$model->id)); ?>
                </div>
                <div id="tabs-1">
                <?php $this->widget('AW_AttList', array('ticket_id'=>$model->id)); ?>
                </div>
                <div id="tabs-2">
                <?php $this->widget('LW_LinksList', array('ticket_id'=>$model->id)); ?>
                </div>
                <div id="tabs-3">
                <?php $this->widget('TW_InnerTaskList', array('ticket_id'=>$model->id)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="span-side last" style="width:30%;">
        <br/>
        <?= $this->renderPartial('_view_users', ['model'=>$model]) ?>
        <?= $this->renderPartial('_view_dates', ['model'=>$model]) ?>
    </div>
</div>

<script language="javascript">
	function updateLink(num, hide) {
		var link = $("#link-tabs-"+num+" a");
		var summary = $("#tabs-"+num+" .summary");
		if (num == 3) {
			summary = $(".summary_custome");
		}
		var count = 0;
		if (summary.length == 1) count = new RegExp("of ([0-9]+)").exec(summary.html())[1];
		if (summary.length == 2) count = parseInt(new RegExp("of ([0-9]+)").exec($(summary[0]).html())[1]) + parseInt(new RegExp("of ([0-9]+)").exec($(summary[1]).html())[1]);
		if (count == 0 && hide) link.hide();
		link.html(link.html() + " (" + count + ")");
		if (num == 3) {
			summary.hide();
		}
	}

	$(function(){
		updateLink(0, false);
		updateLink(1, false);
		updateLink(2, false);
		updateLink(3, false);
        //updateLink(4, false);
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