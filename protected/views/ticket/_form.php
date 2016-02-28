<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
?>
<script src="js/tinymce/tinymce.min.js"></script>
<script>
        tinymce.init({
			selector:'textarea.editor',
			plugins : 'link',
			menubar:false,
			language : 'ru',
			statusbar: true,
			resize: true,
			toolbar:'styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat',
			removeformat_selector : 'b,strong,em,i,span,ins,ul,li,ol'
		});
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticket-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля со <span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_type_id'); ?>
		<?php echo $form->dropDownList($model,'ticket_type_id', CHTML::listData(TicketType::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'ticket_type_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('maxlength'=>1023,'class'=>'editor')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'initial_version'); ?>
		<?php 
		if ($model->isNewRecord) $model->initial_version = $model->project->current_version;
		echo $form->textField($model,'initial_version',array('size'=>25,'maxlength'=>25));
		?>
		<?php echo $form->error($model,'initial_version'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php echo $form->dateField($model,'due_date'); ?>
		<?php echo $form->error($model,'due_date'); ?>
		<?php echo CHTML::checkBox("estimate_start_auto", true, array('onclick'=>'EstimateStartAutoChange();')) . " Автоматически обновить дату начала "; ?>
	</div>
	
	<div class="row" id="divEstimateStartDate">
		<?php echo $form->labelEx($model,'estimate_start_date'); ?>
		<?php echo $form->dateField($model,'estimate_start_date'); ?>
		<?php echo $form->error($model,'estimate_start_date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'estimate_time'); ?>
		<?php //TODO: switch to timeField
		echo $form->textField($model,'estimate_time'); ?>
		<?php echo $form->error($model,'estimate_time'); ?>
	</div>
	
	<div class="row updateOnly">
		<?php echo $form->labelEx($model,'worked_time'); ?>
		<?php echo $form->textField($model,'worked_time'); ?>
		<?php echo $form->error($model,'worked_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'priority_id'); ?>
		<?php echo $form->dropDownList($model,'priority_id', CHTML::listData(Priority::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'priority_id'); ?>
	</div>
<?php /*
	<div class="row updateOnly">
		<?php echo $form->labelEx($model,'status_id'); ?>
		<?php echo $form->dropDownList($model,'status_id', CHTML::listData(Status::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>

	<div class="row updateOnly">
		<?php echo $form->labelEx($model,'resolution_id'); ?>
		<?php echo $form->dropDownList($model,'resolution_id', CHTML::listData(Resolution::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'resolution_id'); ?>
	</div>
*/
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'owner_user_id'); ?>
		<?php echo $form->dropDownList($model,'owner_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'owner_user_id'); ?>
		<?php echo CHTML::checkBox("responsible_auto", true, array('onclick'=>'ResponsibleAutoChange();')) . " Обновить ответственного"; ?>
	</div>

	<div class="row" id="divResponsible">
		<?php echo $form->labelEx($model,'responsible_user_id'); ?>
		<?php echo $form->dropDownList($model,'responsible_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'responsible_user_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tester_user_id'); ?>
		<?php echo $form->dropDownList($model,'tester_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Не выбрано --')); ?>
		<?php echo $form->error($model,'tester_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_ticket_id'); ?>
		<?php echo $form->dropDownList($model,'parent_ticket_id', CHTML::listData(Ticket::model()->findAll('ticket_type_id = 1 AND status_id < 6 AND project_id = '.$model->project_id), 'id', 'subject'), array('empty' => '-- Пусто --')); ?>
		<?php echo $form->error($model,'parent_ticket_id'); ?>
	</div>
	
	<?php if (!$model->isNewRecord) { ?>
	<div class="row">
		<?php
		$comment = new Comment();
		echo $form->label($comment,'text', array('class'=>'wfc_comment'));
		echo $form->textArea($comment,'text',array('maxlength'=>1000, 'class'=>'wfc_comment'));
		?>
	</div>
	<?php } ?>
	
	<?php if (User::CheckLevel(20)) { ?>
	<div class="row">
		<label>Разослать уведомления</label>
		<input type="checkbox" name="sendNotifications" checked />
	</div>
	<?php } ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(function() {
	$("#Ticket_ticket_type_id").change(TicketType_OnChange);
	$(".datepicker").datepicker({ firstDay: 1, showOn: "both", dateFormat: "yy-mm-dd" });
	initialVersionDefault = $("#Ticket_initial_version").val();
	TicketType_OnChange();
});

function TicketType_OnChange() {
	setParentVisibility();
	setInitialVersionVisibility();
}

var initialVersionDefault;
function setInitialVersionVisibility () {
	if($("#Ticket_ticket_type_id").val() != 3) $("#Ticket_initial_version").val("").closest(".row").hide();
	else $("#Ticket_initial_version").val(initialVersionDefault).closest(".row").show();
}

function setParentVisibility() {
	if($("#Ticket_ticket_type_id").val() == 1) $("#Ticket_parent_ticket_id").val("").closest(".row").hide();
	else $("#Ticket_parent_ticket_id").closest(".row").show();
}

function EstimateStartAutoChange() {
	if ($("#estimate_start_auto").prop("checked")) {
		$("#divEstimateStartDate").hide();
	} else {
		$("#divEstimateStartDate").show();
	}
}

$("#divEstimateStartDate").hide();

function ResponsibleAutoChange() {
	if ($("#responsible_auto").prop("checked")) {
		$("#divResponsible").hide();
	} else {
		$("#divResponsible").show();
	}
}

$("#divResponsible").hide();
</script>