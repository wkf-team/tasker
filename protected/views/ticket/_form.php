<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
?>
<script src="js/tinymce/tinymce.min.js"></script>
<script>
        tinymce.init({
			selector:'textarea',
			plugins : 'link',
			menubar:false,
			language : 'ru',
			statusbar: true,
			resize: true
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
		<?php echo $form->textArea($model,'description',array('maxlength'=>1023)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'estimate_start_date'); ?>
		<?php echo $form->dateField($model,'estimate_start_date'); ?>
		<?php echo $form->error($model,'estimate_start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'due_date'); ?>
		<?php echo $form->dateField($model,'due_date'); ?>
		<?php echo $form->error($model,'due_date'); ?>
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
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tester_user_id'); ?>
		<?php echo $form->dropDownList($model,'tester_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name'), array('empty' => '-- Не выбрано --')); ?>
		<?php echo $form->error($model,'tester_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'responsible_user_id'); ?>
		<?php echo $form->dropDownList($model,'responsible_user_id', CHTML::listData(User::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'responsible_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_ticket_id'); ?>
		<?php echo $form->dropDownList($model,'parent_ticket_id', CHTML::listData(Ticket::model()->findAll('ticket_type_id = 1 AND status_id < 3'), 'id', 'subject'), array('empty' => '-- Пусто --')); ?>
		<?php echo $form->error($model,'parent_ticket_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(function() {
	$("#Ticket_ticket_type_id").change(setParentVisibility);
	$(".datepicker").datepicker({ firstDay: 1, showOn: "both", dateFormat: "yy-mm-dd" });
	setParentVisibility();
});

function setParentVisibility() {
	if($("#Ticket_ticket_type_id").val() == 1) $("#Ticket_parent_ticket_id").val("").closest(".row").hide();
	else $("#Ticket_parent_ticket_id").closest(".row").show();
}
</script>