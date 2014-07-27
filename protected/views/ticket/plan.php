<?php
/* @var $this TicketController */
/* @var $model Ticket */

$this->breadcrumbs=array(
	'Задачи'=>array('index'),
	'Планирование',
);
?>

<style>
.settings {
	width:60px;
	position: relative;
	left: 150px;
	top: -15px;
}
</style>

<h1>Планирование</h1>
<!--
<table><tr><td>
<?php echo CHtml::label("Все - Только новые", "filter_new"); ?>
<div id='filter_new' class="settings"></div>
</td><td>
<?php echo CHtml::label("План - Баланс", "filter_balance"); ?>
<div id='filter_balance' class="settings"></div>
</td></tr></table>
-->

<table style="width:900px;">
<tr>
	<th width=150px></th>
	<th>id</th>
	<th style="width:400px;"><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('subject'));?></th>
	<th><?php 
	//echo CHtml::encode($dataProvider->model->getAttributeLabel('due_date'));
	echo CHtml::encode($dataProvider->model->getAttributeLabel('owner_user_id'));?></th>
	<th class="filter_balance_only"><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('estimate_start_date'));?></th>
	<th class="filter_balance_only"><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('due_date'));?></th>
	<th class="filter_balance_only"><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('estimate_time'));?></th>
	<th class="filter_balance_only">Пред.</th>
</tr>
	
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_viewPlan',
)); ?>

</table>
<button id="btnAddEpic">Add new epic</button><button id="btnStartAISE">Start AISE</button>
<div id="editQ">
	<?php $this->renderPartial('_formQ', array('model' => $model));?>
</div>

<script>

$(function () {
	// SETTINGS
	$("#filter_new").slider({
		animate : "slow",
		max		: 1,
		min 	: 0,
		step	: 1,
		value	: <?php echo $filter_new; ?>,
		change: function( event, ui ) {
			setTimeout(function () {
				window.location.href = "<?php echo CHtml::normalizeUrl(array("ticket/plan")); ?>&filter_new=" + $("#filter_new").slider("value");
			}, 300);
		}
	});
	$("#filter_balance").slider({
		animate : "slow",
		max		: 1,
		min 	: 0,
		step	: 1,
		value	: 1,
		change 	: function( event, ui ) {
			alert('Not implemented yet');
			//if ($("#filter_balance").slider("value")) $(".filter_balance_only").show();
			//else $(".filter_balance_only").hide();
		}
	});
	// and default is hide
	//$(".filter_balance_only").hide();
	
	// BUTTONS
	$(".btnQEdit").button({
      icons: {
        primary: "ui-icon-pencil"
      },
      text: false
    }).click(OpenEditDialog);
	/*$(".btnOpen").button({
      icons: {
        primary: "ui-icon-folder-open"
      },
      text: false
    }).click();*/
	$(".btnDelete").button({
      icons: {
        primary: "ui-icon-trash"
      },
      text: false
    });
	$(".btnAdd").button({
      icons: {
        primary: "ui-icon-plus"
      },
      text: false
    }).click(OpenNewDialog);
	/*$(".btnInline").button({
      icons: {
        primary: "ui-icon-seek-next"
      },
      text: false
    }).click(StartAISE);*/
	$("#btnAddEpic").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    }).click(OpenNewDialog);
	$("#btnStartAISE").button({
      icons: {
        primary: "ui-icon-seek-next"
      },
      text: false
    }).click(StartAISE);
	
	// DIALOGS
	$("#editQ").dialog({
		autoOpen	: false,
		modal		: true,
		buttons		: {
			OK		: SubmitActiveForm,
			Cancel	: function () {$("#editQ").dialog("close");}
		}
	});
	$('#editQ').keypress(function(e) {
		if (e.keyCode == $.ui.keyCode.ENTER) {
			  SubmitActiveForm(null);
		}
	});
	
	function OpenEditDialog(ev) {
		$(".errorSummary").html("").hide();
		// get data
		eval("var data = " + $(ev.target).closest("tr").find("#data").val());
		// select dialog
		var dialog = $("#editQ");
		if (data.ticket_type_id == 1) dialog.dialog("option", "title", "Эпик");
		else dialog.dialog("option", "title", "Задача");
		// fill form
		dialog.find("#Ticket_id").val(data.id);
		dialog.find("#Ticket_subject").val(data.subject);
		dialog.find("#Ticket_description").val(data.description);
		dialog.find("#Ticket_due_date").val(data.due_date);
		dialog.find("#Ticket_priority_id").val(data.priority_id);
		dialog.find("#Ticket_estimate_time").val(data.estimate_time);
		dialog.find("#Ticket_owner_user_id").val(data.owner_user_id);
		dialog.find("#Ticket_ticket_type_id").val(data.ticket_type_id);
		dialog.find("#Ticket_parent_ticket_id").val(data.parent_ticket_id);
		dialog.find("#blocked_by").val(data.blocked_by);
		// open
		dialog.dialog("open");
	}
	
	function OpenNewDialog(ev) {
		$(".errorSummary").html("").hide();
		// select dialog
		var isTask = $(ev.target).parent().hasClass("btnAdd");
		var dialog = $("#editQ");
		if (isTask) {
			dialog.dialog("option", "title", "Задача");
			eval("var parent_ticket_id = " + $(ev.target).closest("tr").find("#data").val() + ".id");
		} else {
			dialog.dialog("option", "title", "Эпик");
			parent_ticket_id = null;
		}
		// fill form
		dialog.find("#Ticket_id").val(null);
		dialog.find("#Ticket_subject").val(null);
		dialog.find("#Ticket_description").val(null);
		dialog.find("#Ticket_due_date").val(null);
		dialog.find("#Ticket_priority_id").val(newTicketData.priority_id);
		dialog.find("#Ticket_estimate_time").val(null);
		dialog.find("#Ticket_owner_user_id").val(newTicketData.owner_user_id);
		dialog.find("#Ticket_ticket_type_id").val(isTask ? 2 : 1);
		dialog.find("#Ticket_parent_ticket_id").val(parent_ticket_id);
		dialog.find("#blocked_by").val(null);
		// open
		dialog.dialog("open");
	}
	
	function SubmitActiveForm(ev) {
		var activeForm = $("#editQ").find("form");
		$.post(activeForm.get(0).action, activeForm.serialize(), EditTicketResponse);
	}
	
	function EditTicketResponse(data) {
		if (data.length > 2) $(".errorSummary").html("Заполните все обязательные поля!").show();
		else location.reload();
	}
	
	function StartAISE(ev) {
		alert("Amazing inline statemachine hotkey editing :)");
	}
	
	newTicketData.priority_id = $("#editQ").find("#Ticket_priority_id").val();
	newTicketData.owner_user_id = $("#editQ").find("#Ticket_owner_user_id").val();
});

var newTicketData = {};
</script>