<table>
<tr>
	<th></th>
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
	'itemView'=>'application.views.ticket._viewPlan',
	'summaryText'=>'',
	'viewData'=>['offset'=>0]
)); ?>

</table>
<div id="editQ">
	<?php $this->render('application.views.ticket._formQ', array('model' => $model));?>
</div>

<script>

$(function () {
	// BUTTONS
	$(".btnQEdit").button({
      icons: {
        primary: "ui-icon-pencil"
      },
      text: false
    }).click(OpenEditDialog);
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
	$("#btnAddEpic").button({
      icons: {
        primary: "ui-icon-flag"
      },
      text: true
    }).click(OpenNewDialog);
	$("#btnAddStory").button({
      icons: {
        primary: "ui-icon-note"
      },
      text: true
    }).click(OpenNewDialog);
	
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
		switch (data.ticket_type_id) {
			case 1: dialog.dialog("option", "title", "Эпик"); break;
			case 2: dialog.dialog("option", "title", "История"); break;
			case 3: dialog.dialog("option", "title", "Задача"); break;
			case 4: dialog.dialog("option", "title", "Ошибка"); break;
			default: dialog.dialog("option", "title", "Задача"); break;
		}
		// fill form
		dialog.find("#Ticket_id").val(data.id);
		dialog.find("#Ticket_subject").val(data.subject);
		dialog.find("#Ticket_description").val(data.description);
		dialog.find("#Ticket_due_date").val(data.due_date);
		dialog.find("#Ticket_priority_id").val(data.priority_id);
		dialog.find("#Ticket_estimate_time").val(data.estimate_time);
		dialog.find("#Ticket_owner_user_id").val(data.owner_user_id);
		dialog.find("#Ticket_tester_user_id").val(data.tester_user_id);
		dialog.find("#Ticket_ticket_type_id").val(data.ticket_type_id);
		dialog.find("#Ticket_parent_ticket_id").val(data.parent_ticket_id);
		dialog.find("#blocked_by").val(data.blocked_by);
		// open
		dialog.dialog("open");
	}
	
	function OpenNewDialog(ev) {
		$(".errorSummary").html("").hide();
		// select dialog
		var isInline = $(ev.target).parent().hasClass("btnAdd");
		var dialog = $("#editQ");
		if (isInline) {
			eval("var data = " + $(ev.target).closest("tr").find("#data").val());
			var parent_ticket_id = data.id;
			if (data.ticket_type_id == 1) {
				dialog.dialog("option", "title", "История");
				var new_type_id = 2;
			} else {
				dialog.dialog("option", "title", "Задача");
				var new_type_id = 3;
			}
		} else {
			parent_ticket_id = null;
			if  ($(ev.target).parent().hasClass("btnAddEpic")) {
				dialog.dialog("option", "title", "Эпик");
				new_type_id = 1;
			} else {
				new_type_id = 2;
				dialog.dialog("option", "title", "История");
			}
		}
		// fill form
		dialog.find("#Ticket_id").val(null);
		dialog.find("#Ticket_subject").val(null);
		dialog.find("#Ticket_description").val(null);
		dialog.find("#Ticket_due_date").val(null);
		dialog.find("#Ticket_priority_id").val(newTicketData.priority_id);
		dialog.find("#Ticket_estimate_time").val(null);
		dialog.find("#Ticket_owner_user_id").val(newTicketData.owner_user_id);
		dialog.find("#Ticket_tester_user_id").val(null);
		dialog.find("#Ticket_ticket_type_id").val(new_type_id);
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