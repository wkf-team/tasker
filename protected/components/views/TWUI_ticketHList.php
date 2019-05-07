<script src="js/tinymce/tinymce.min.js"></script>
<table>
<tr>
	<th style="width: 75px;"></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('subject'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('status_id'));?></th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('priority_id'));?></th>
	<th>Пред.</th>
	<th><?php echo CHtml::encode($dataProvider->model->getAttributeLabel('owner_user_id'));?></th>
	<th>Оценка (ч / SP)</th>
</tr>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.views.ticket._viewPlan',
	'summaryText'=>'',
	'viewData'=>[
		'offset'=>0,
		'filterForBacklog'=>$filterForBacklog,
		'iteration_id'=>$iteration_id,
		'noChildren'=>$noChildren,
		'allChildren'=>$allChildren,
	]
)); ?>

</table>
<div id="editQ">
	<?php $this->render('application.views.ticket._formQ', array('model' => $model));?>
</div>
<? if ($showFooterButtons) { ?>
<button id="btnAddEpic" class="btnAddEpic">New Epic</button>
<button id="btnAddStory">New Single UserStory</button>
<? } ?>

<script>
var dialog_scroll;
function restoreScroll() {
    setTimeout(function () { console.log(window.scrollY); window.scrollTo(0, dialog_scroll); console.log(window.scrollY); }, 100);
}

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
	$(".btnAddToIteration").button({
      icons: {
        primary: "ui-icon-pin-w"
      },
      text: false
    });
	$(".btnRemoveFromIteration").button({
      icons: {
        primary: "ui-icon-check"
      },
      text: false
    });
	$(".btnMorePriority").button({
      icons: {
        primary: "ui-icon-arrow-1-n"
      },
      text: false
    });
	$(".btnLessPriority").button({
      icons: {
        primary: "ui-icon-arrow-1-s"
      },
      text: false
    });
	
	// DIALOGS
	$("#editQ").dialog({
		autoOpen	: false,
		modal		: true,
		width		: 900,
		buttons		: {
			OK		: SubmitActiveForm,
			Cancel	: CancelForm,
		}
	});
	$('#editQ').keypress(function(e) {
		if (e.keyCode == $.ui.keyCode.ENTER) {
			  SubmitActiveForm(null);
		}
	});
	
	function CancelForm() {
		if ("tinymce" in window) tinymce.remove();
		$("#editQ").dialog("close");
	}
	
	function OpenEditDialog(ev) {
        dialog_scroll = window.scrollY;
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
		dialog.find("#Ticket_story_points").val(data.story_points);
		dialog.find("#Ticket_owner_user_id").val(data.owner_user_id);
		dialog.find("#Ticket_tester_user_id").val(data.tester_user_id);
		dialog.find("#Ticket_ticket_type_id").val(data.ticket_type_id);
		dialog.find("#Ticket_parent_ticket_id").val(data.parent_ticket_id);
		dialog.find("#blocked_by").val(data.blocked_by);
		// open
		dialog.dialog("open");
        
		if ("tinymce" in window) tinymce.init({
			selector:'textarea.editor',
			plugins : 'link',
			menubar:false,
			language : 'ru',
			statusbar: true,
			resize: true,
			toolbar:'styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat',
			removeformat_selector : 'b,strong,em,i,span,ins,ul,li,ol'
		});
        
        restoreScroll();
	}
	
	function OpenNewDialog(ev) {
        dialog_scroll = window.scrollY;
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
		dialog.find("#Ticket_story_points").val(null);
		dialog.find("#Ticket_owner_user_id").val(newTicketData.owner_user_id);
		dialog.find("#Ticket_tester_user_id").val(null);
		dialog.find("#Ticket_ticket_type_id").val(new_type_id);
		dialog.find("#Ticket_parent_ticket_id").val(parent_ticket_id);
		dialog.find("#blocked_by").val(null);
		// open
		dialog.dialog("open");
		
		if ("tinymce" in window) tinymce.init({
			selector:'textarea.editor',
			plugins : 'link',
			menubar:false,
			language : 'ru',
			statusbar: true,
			resize: true,
			toolbar:'styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat',
			removeformat_selector : 'b,strong,em,i,span,ins,ul,li,ol'
		});
        restoreScroll();
	}
	
	function SubmitActiveForm(ev) {
		if ("tinymce" in window) tinyMCE.triggerSave();
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
	
	$(".ticket-collapsed").hide();

});

var newTicketData = {};

function collapse(id)
{
	$(".ticket-"+id+"-collapsed").show();
	$(".ticket-"+id+"-expanded").hide();
}

function expand(id)
{
	$(".ticket-"+id+"-collapsed").hide();
	$(".ticket-"+id+"-expanded").show();
}

</script>